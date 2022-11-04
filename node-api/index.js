var options = {
    host: 'localhost',
    port: 3050,
    database: 'D:\\projects\\HyOSystem\\code\\php-api\\database\\data\\EJEMPLO.FDB',
    username: 'SYSDBA',
    password: 'Pa$$w0rd',
    retryConnectionInterval: 1000,
};

var express = require('express'),
    app = express(),
    cors = require('cors'),
    bodyParser = require('body-parser');

const Firebird = require('node-firebird-driver-native');

const firebirdQuery = async (query, params) => {
    const client = Firebird.createNativeClient(Firebird.getDefaultLibraryFilename());
    let attachment = null;
    try {
        attachment = await client.connect(options.database, options);
    } catch (error) {
        throw error;
    }

    let transaction = null;

    try {
        transaction = await attachment.startTransaction();
    } catch (error) {
        throw error;
    }

    try {
        const resultSet = await attachment.executeQuery(transaction, query, params);
        const rows = await resultSet.fetchAsObject()
        await resultSet.close();
        await transaction.commit();
        await client.dispose();
        return rows;
    } catch (error) {
        console.log(error);
        await transaction.rollback();
        await client.dispose();
        throw error;
    }
}

const firebirdExecute = async (query, params) => {
    const client = Firebird.createNativeClient(Firebird.getDefaultLibraryFilename());
    let attachment = null;
    try {
        attachment = await client.connect(options.database, options);
    } catch (error) {
        throw error;
    }

    let transaction = null;

    try {
        transaction = await attachment.startTransaction();
    } catch (error) {
        throw error;
    }

    try {
        const resultSet = await attachment.execute(transaction, query, params);
        await transaction.commit();
        await client.dispose();
        return 1;
    } catch (error) {
        console.log(error);
        await transaction.rollback();
        await client.dispose();
        throw error;
    }
}

app.use(bodyParser.urlencoded({ extended: false }))
app.use(bodyParser.json())
app.use(cors({
    origin: '*'
}));

app.get('/', function (req, res) {
    res.redirect('/index.html');
});

app.post('/api/login', async (req, res) => {
    const query = 'SELECT * FROM USUARIOS WHERE USUARIO=? AND CLAVE=?';
    const params = [req.body.usuario, req.body.clave];
    let resultado = [];
    try {
        resultado = await firebirdQuery(query, params);
        if (!resultado.length) {
            res.status(401).send({ error: 'Usuario no encontrado' });
            return;
        }

        res.status(200).send(resultado[0]);
    } catch (error) {
        res.status(500).send({ error: error.toString() });
    }
});

app.get('/api/users', async (req, res) => {
    const query = 'SELECT * FROM USUARIOS';
    const params = [];
    let resultado = [];
    try {
        resultado = await firebirdQuery(query, params);
        if (!resultado.length) {
            res.status(401).send({ error: 'Usuario no encontrado' });
            return;
        }

        res.status(200).send(resultado);
    } catch (error) {
        res.status(500).send({ error: error.toString() });
    }
});

app.post('/api/users', async (req, res) => {
    let maxId = 0;
    try {
        const query1 = 'SELECT max(ID) AS ID FROM USUARIOS';
        const rows = await firebirdQuery(query1, []);
        if (rows.length == 0)
            maxId = 1;
        else
            maxId = rows[0].ID + 1;
    } catch (error) {
        res.status(500).send({ error: error.toString() });
        return;
    }

    const query = 'INSERT INTO USUARIOS(ID, USUARIO, CLAVE, NOMBRES) VALUES(?,?,?,?)';
    const params = [maxId, req.body.usuario, req.body.clave, req.body.nombres];
    let resultado = -1;
    try {
        resultado = await firebirdExecute(query, params);
        req.body.id = maxId;
        res.status(200).send(req.body);
    } catch (error) {
        res.status(500).send({ error: error.toString() });
    }
});

app.post('/api/users/device', async (req, res) => {
    try {
        const query1 = 'SELECT * FROM USUARIOS_DISPOSITIVOS WHERE TOKEN_DISPOSITIVO=?';
        const rows = await firebirdQuery(query1, [req.body.tokenDispositivo]);
        if(rows.length == 1) {
            res.status(200).send(req.body);
            return;
        }
    } catch (error) {
        res.status(500).send({ error: error.toString() });
        return;
    }

    const query = 'INSERT INTO USUARIOS_DISPOSITIVOS(TOKEN_DISPOSITIVO, USUARIO_ID) VALUES(?,?)';
    const params = [req.body.tokenDispositivo, req.body.usuarioId];
    let resultado = -1;
    try {
        resultado = await firebirdExecute(query, params);
        res.status(200).send(req.body);
    } catch (error) {
        res.status(500).send({ error: error.toString() });
    }
});

var port = 9000;
app.listen(port);
console.log('Server started at http://localhost:' + port);

