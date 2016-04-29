var URL = {},
    TOKEN,
    USER = {
        name: null,
        id: 0
    };


//TEMP DEBUG
var tmpx, tmpy, tmpz, tmpw;

window.onload = function(){

    _('pageonloading').style.display = "none";

    _qs("input[name=login]").focus();
    //cancel();

    URL.base = window.location.origin;
    URL.data = URL.base+'/data/';
    URL.file = URL.base+'/file/';
    URL.user = URL.base+'/user/';

    TOKEN = _passw.gen(40);


    _('file').onchange = function(e){
        var up = new lib.upload(URL.file, TOKEN, _('file'), USER.id);
        var f = up.fileList();
        if(f === false) f = "Tamanho excedeu o limite. Escolha <b>menos</b> arquivos.";
        _("tabcontent").innerHTML = f;
    }

    //LOGIN 
    _qs("input[name=password]").onkeyup = function(e){
        if(e.keyCode == 13) login();
    }

    _qs("input[name=login]").onkeyup = function(e){
        if(e.keyCode == 13) _qs("input[name=password]").focus();
    }
}


/**
 * PROGRESS BAR
 */
function progressBar(data){
    _qs('.progress').style.display = "block";
    var p = _('progressbar');
    p.style.width = data;
    p.innerHTML = data;

    if(data === '100%'){
        _("tabcontent").innerHTML = "Arquivos enviados.";
        setTimeout(function(){_qs('.progress').style.display = "none";}, 1000)
    }
}

function receiveData(d){
    console.log(d)

    tmpx = d;

    if("undefined" !== typeof d.token)

    var t = '(indefinido)';
    if(d.length) t = d.length.toLocaleString();
    
    _("tabcontent").innerHTML = "Recebido "+t+" bytes";

    if("undefined" !== typeof d.user){
        var user = d.user;
        USER.id = user.id;
        var tmp = '<h1>'+user.name+'</h1><p><i>'+d.html+'</i></p>';
        $("#top").html(tmp);
    }
}


//LOGIN with RSA 1024
function login(){
    var data = {}
    data.login = $("input[name=login]").val();
    data.password = $("input[name=password]").val();

    lib.ajax(URL.user, data, TOKEN, USER.id, PUBKEY, 'rsa')
       .done(function(d){
            if("undefined" === typeof d.user){
                _('msgLogin').innerHTML = "Verifique seu login e senha!";
                setTimeout(function(){
                    _('msgLogin').innerHTML = '';
                    _qs('input[name=login]').focus();},3000)
                return false;
            }

            USER = d.user;
            _('top').innerHTML = '<h1>'+USER.name+'</h1><p><i>'+d.html+'</i></p>';
            _('msgLogin').innerHTML = '';
            $(".lg-content").fadeOut();

       })
       .progress(progressBar)
       .send();
}

//Teste de envio de dados
function run(){
    var data = {}
    data.parametro1 = $("#par1").val();
    data.parametro2 = $("#par2").val();
    data.parametro3 = $("#par3").val();
    data.returno = $("#returno").val();

    var a = new lib.ajax(URL.data, data, TOKEN, USER.id);
    a.done(receiveData)
     .progress(progressBar)
     .send();
}

function fileSend(){
    _("tabcontent").innerHTML = "Empacotando e enviado...";

    var fsend = new lib.upload(URL.file, TOKEN, _('file'), USER.id);
    fsend.send(receiveData,progressBar);
}

function fileRequest(){
    var f = new lib.download(URL.file, TOKEN, 'data.zip', USER.id, receiveData);
}

