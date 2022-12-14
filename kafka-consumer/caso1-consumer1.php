<?php
$conf = new RdKafka\Conf();

// Set the group id. This is required when storing offsets on the broker
$conf->set('group.id', 'GrupoConsumidores1');

// Emit EOF event when reaching the end of a partition
$conf->set('enable.partition.eof', 'true');

$rk = new RdKafka\Consumer($conf);
$rk->addBrokers("192.168.204.129");

$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.commit.interval.ms', 100);

// Set the offset store method to 'file'
$topicConf->set('offset.store.method', 'broker');

// Alternatively, set the offset store method to 'none'
// $topicConf->set('offset.store.method', 'none');

// Set where to start consuming messages when there is no initial offset in
// offset store or the desired offset is out of range.
// 'earliest': start from the beginning
// 'latest': start from the beginning
$topicConf->set('auto.offset.reset', 'earliest');

$topic = $rk->newTopic("cliente1-libretas", $topicConf);

// Start consuming partition 0
$topic->consumeStart(0, RD_KAFKA_OFFSET_STORED);

while (true) {
    $message = $topic->consume(0, 120*10000);
    switch ($message->err) {
        case RD_KAFKA_RESP_ERR_NO_ERROR:
            echo 'partition='.$message->partition . ', offset=' . $message->offset .PHP_EOL;
            echo json_encode($message).PHP_EOL;
            $url = 'https://www.africau.edu/images/default/sample.pdf';    
            $aleatorio = rand(1, 1000);
            $file_name = date('Y-m-d-H-i-s-a') . '-' . $aleatorio. '-consumer1.pdf';
            if (file_put_contents($file_name, file_get_contents($url)))
            {
                echo "File downloaded successfully".PHP_EOL;
            }
            else
            {
                echo "File downloading failed.".PHP_EOL;
            }
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