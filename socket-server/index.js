/*=============================================
= DESARROLLADO POR SANTIAGO BLANCO VILCHEZ 2018 
=============================================*/

/*=====  santiago.blanco.vilchez@gmail.com ======*/

// =====================================
// = DEPENDENCIAS
// ======================================

// libreria encargada de establecer la conexión con el socket.
const io = require('socket.io')(3000);
// libreria apra manejo de sistema de archivos
var fs = require('fs');
// libreria para manejo de arrays etc
var _ = require('lodash')
// libreria pra obtener información de uso del disco
const disk = require('diskusage');
// libreria para info de la cpu
var osUtils = require('os-utils');
// parametros de configuracion
var config = require("./config")

console.log(config)

// =====================================
// = CONFIGURACIONES (EDITAR ARCHIVO SOCKET-SERVER PARA EDITAR LAS RUTAS.)
// ======================================

var supervisordLogPath = config.supervisordLogPath

var supervisorConfPath = config.supervisorConfPath

var queuePath = config.queuePath

let path = config.path

// =====================================
// = REDIS
// ======================================

var redis = require("redis"),
  client = redis.createClient();

var sub = redis.createClient(),
  pub = redis.createClient();

// =====================================
// = MONITOREO DE USUARIOS CONECTADOS
// ======================================

var users = []
var intervalUsers = {}

// estas variables son para el monitoreo en la pagina de administración.
var intervalLogs = {}
var intervalIndicators = {}

// permite depterminar si debe simplemente actualizar la fecha de conexión
// o agregar el usuario
function userConnected(data) {
  var user = _.find(users, {
    id: data.user.id
  })

  if (user) {
    var index = _.findIndex(users, { id: data.user.id });
    var user = data.user
    user.connectedDate = new Date(data.date)
    users.splice(index, 1, user);

  } else {
    var user = data.user
    user.connectedDate = new Date(data.date)
    users.push(user)
  }
}


// permite verificar al usuario
function verifyUsers() {
  intervalUsers = setInterval(function() {
    _.each(users, checkIdle)
  }, 1000)
}

// revisa cada medio minuto que usuarios estan conectados
function checkIdle(user) {
  if (user) {
    var date = new Date();
    // Medio minuto en milisegundos
    var FIVE_MIN = 0.5 * 60 * 1000;
    if ((date - new Date(user.connectedDate)) > FIVE_MIN) {
      _.remove(users, {
        id: user.id
      });
    }
  }
  emitUsersConnected()
}

// emite el numero de usuarios conectados
function emitUsersConnected() {
  io.sockets.in("adminUsers").emit('users', {
    connected: users.length,
    users: users
  })
}


// =====================================
// = AL ESTABLECER CONEXIÓN CON EL SOCKET
// ======================================
function onConnected(socket) {
  // acción que se ejecuta cuando se conecta un usuario
  socket.on("connected", userConnected)

  // empieza a escuchar administración de usuarios
  socket.on('adminUsers', function() {
    socket.join('adminUsers')
    verifyUsers()
  })

  // empieza a escuchar administración
  socket.on('admin', function() {
    socket.join('admin');
    intervalLogs = setInterval(emitLogs, 1000)
    intervalIndicators = setInterval(emitIndicators, 1000)
  })

  // empieza a escuchar en un room especifico
  socket.on('room', function(room) {
    sub.subscribe('room/' + room);
    socket.join('room/' + room);
  })

  // deja de escuchar un determinado modelo
  socket.on('room-leave', function(room) {
    socket.leave('room/' + room);
    sub.unsubscribe('room/' + room);
  })

  // deja de escuchar admin
  socket.on('admin-leave', function(room) {
    socket.leave('admin');
    clearInterval(intervalLogs)
    clearInterval(intervalIndicators)
  })

  // de escuchar usuarios
  socket.on('adminUsers-leave', function() {
    socket.leave('adminUsers')
    clearInterval(intervalUsers)
  })
}


// =====================================
// = RECIBIR MENSAJES DE REDIS
// ======================================
sub.on('message', function(channel, data) {
  console.log(data)
  io.sockets.in(channel).emit('message', {
    data: JSON.parse(data),
    channel: channel
  });
});


// accion que se ejecuta si hay un error
function onError(error) {
  console.log(error)
}


// =====================================
// = ADMINISTRACIÓN
// ======================================
var cpuUsage = 0.10
// emite los indicadores de CPU 
function emitIndicators() {
  osUtils.cpuUsage(function(v) {
    cpuUsage = v
  });

  disk.check(path, function(err, info) {
    if (!err) {
      io.sockets.in("admin").emit('diskInfo', {
        free: info.free,
        total: info.total,
        cpuUsage: parseInt((cpuUsage * 100).toFixed(2))
      })
    }
  });
}

// emite los logs de los archivos
function emitLogs() {
  readSupervisord()
  readQueue()
  readSupervisorConf()
}

// lee y emite el archivo de logs de supervisor
function readSupervisord() {
  fs.readFile(supervisordLogPath, { encoding: 'utf-8' }, function(err, contents) {
    if (!err) {
      io.sockets.in("admin").emit("logsSupervisor", contents);
    }
  });
}

// lee y emite el archivo de logs de supervisor
function readSupervisorConf() {
  fs.readFile(supervisorConfPath, { encoding: 'utf-8' }, function(err, contents) {
    if (!err) {
      io.sockets.in("admin").emit("logsSupervisorConfig", contents);
    }
  });
}

// lee y emite el archivo de los de las queues
function readQueue() {
  fs.readFile(queuePath, { encoding: 'utf-8' }, function(err, contents) {
    if (!err) {
      io.sockets.in("admin").emit("logsQueue", contents);
    }
  });
}



// registramos eventos
client.on("error", onError);
// al establecer conexion con el cliente
io.on('connection', onConnected);
