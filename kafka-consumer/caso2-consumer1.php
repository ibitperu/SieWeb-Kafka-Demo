<?php
$conf = new RdKafka\Conf();

// Set the group id. This is required when storing offsets on the broker
$conf->set('group.id', 'GrupoConsumidores2');
// $conf->set('enable.auto.offset.store', 'false');
// Emit EOF event when reaching the end of a partition
$conf->set('enable.partition.eof', 'true');

$rk = new RdKafka\Consumer($conf);
$rk->addBrokers("192.168.204.129");

$topicConf = new RdKafka\TopicConf();
// $topicConf->set('enable.auto.commit', 'false');
// $topicConf->set('auto.commit.interval.ms', 100);

// Set the offset store method to 'file'
$topicConf->set('offset.store.method', 'broker');

// Alternatively, set the offset store method to 'none'
// $topicConf->set('offset.store.method', 'none');

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'earliest': start from the beginning
// 'latest': start from the beginning
$topicConf->set('auto.offset.reset', 'earliest');

$topic = $rk->newTopic("cliente2-libretas", $topicConf);

// Start consuming partition 0
$topic->consumeStart(0, 9);//RD_KAFKA_OFFSET_STORED

while (true) {
    $message = $topic->consume(0, 120 * 10000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo 'partition=' . $message->partition . ', offset=' . $message->offset . PHP_EOL;
            echo json_encode($message) . PHP_EOL;
            // try {
            // throw new Exception("Sucedió un error inesperado");
            //     //code...
            // } catch (\Throwable $th) {
            //     //throw $th;
            //     $topic->offsetStore($message->partition, $message->offset - 1);
            //     throw new Exception("Error controlado");
            // }
            // echo "ok".PHP_EOL;
            // $url = 'https://www.africau.edu/images/default/sample.pdf';  
            // $file_name = date('Y-m-d-H-i-s-a') . '-' . $message->partition . '-' . $message->offset. '-consumer1.pdf';

            $dataPayload = json_decode($message->payload);
            if(isset($dataPayload->user_id)) {
                $bimestre = "2";
                $url = "http://localhost/api/usuarios/$dataPayload->user_id/libreta-notas/$bimestre/pdf";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $resp = curl_exec($curl);
                curl_close($curl);
                var_dump($resp);

                // $form = [
                //     'notification' => [
                //         'title' => 'Libreta de notas disponible: 2202202020.pdf',
                //         'body'   => 'https://www.africau.edu/images/default/sample.pdf',
                //     ],
                //     'to' => 'e-gIABnJY5qi7KHjkiQhxj:APA91bEghhZ5Tsw1pNFUDzE4RJwnLr5k0Xp-BNig8B28Ex2nfh7k_T_4noyrkOfOn1NKi-upiEkWKaMy9I2Fda6wa4WNAyVPFtNiaGxFYeB8wSEGK1F-gfsl7ETW-9USgqlPRJut0CRw'
                // ];
                // header('Content-Type: application/json');
                // $ch = curl_init('https://fcm.googleapis.com/fcm/send');
                // $authorization = "Authorization: Key=AAAA2IEmK9E:APA91bF7yD-aVDTrcyaACO0HLKLNYNztdZh57GwaA3AXUBHi5nPiYWtuo5zgkTmpP0y0GsdFYsX4njgmamoMTviRqMRkhc43rO7oof_z1wCJPQwcQtABTCDXV8TgGSd6yIbvqSWkTrlU";
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_POST, 1);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($form));
                // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

                // // execute!
                // $response = curl_exec($ch);

                // // close the connection, release resources used
                // curl_close($ch);
            }
            // $topic->offsetStore($message->partition, $message->offset);
            break;
        case RD_KAFKA_RESP_ERR__PARTITION_EOF:
            echo "No more messages; will wait for more\n";
            break;
        case RD_KAFKA_RESP_ERR__TIMED_OUT:
            echo "Timed out\n";
            break;
        default:
            throw new \Exception($message->errstr(), $message->err);
            break;
    }
}
echo 'finish';
