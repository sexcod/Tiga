<?php
namespace Land;

use Resource\Main;
use Lib\App;
use Lib\Aes as Aes;
use Lib\User as User;
use Lib\Html as Html;

class Testajax extends Main
{
	public $styles = ['all','main'];
	public $scripts = ['all','lib','main'];


	function index()
	{
		$a = ["\r","\n","-----BEGIN PUBLIC KEY-----","-----END PUBLIC KEY-----"];
		$key = str_replace($a, '', file_get_contents(_CONFIG.'Key/public.key'));
		$this->sendHTML('form', $key);
	}

	function file(){
		$rec = $this->decodePost();

		$user = User::this();

		$id = $user->get('id');
		$path = _WWW.'userfiles/'.$id;
		if(!file_exists($path)) @mkdir($path, 0777);

		//ENVIANDO ARQUIVO
		if (isset($rec->getfile) && 
			file_exists($path.'/'.$rec->getfile)){
				
			$file = file_get_contents($path.'/'.$rec->getfile);
			$file = base64_encode($file);
			$this->sendData(['file'=>$file, 'name'=>$rec->getfile]);			
		}

		//RECEBENDO ARQUIVO
		if(isset($rec->file)){

			$file = base64_decode($rec->file);
			file_put_contents($path.'/data.zip', $file);
			
			$this->sendData($user->get());
		}

		$this->sendData(['error'=>'Nenhum arquivo para enviar ou receber!']);
	}

	function data()
	{
		$o = $this->decodePost();
		
		$this->sendData(['post'=>$_POST, 
			             'decod'=>$o, 
			             'user'=>User::this()->get(),
			             'token'=>User::this()->get('token')]);
	}

	function user()
	{
		$rec = $this->decodePost();
		if(isset($rec->login)){

            //inicializando o usuário => User Singleton Object
            $user = User::this();
            $user->doLogin($rec->login, $rec->password);


            //Verificando se o login foi bem sucedido
            if($user->get('login')){
                
                //Gravando o novo Token no BD
                $user->saveToken($rec->token);
                
                $html = 'This is the Forms data';//$this->getForms();
                $userdata = ['name'=>$user->get('name'),
                             'id'=>$user->get('id'),
                             'level'=>$user->get('level')];

                //Enviando os dados criptografados
                $this->sendData(['user'=>$userdata,
                                 'token'=>$rec->token,
                                 'html'=>$html]);
            }
        }
        //Em casos contrários, retorna erro.
        exit(json_encode(['error'=>'Confira seu Login ou Senha!']));
	}

	final function decodePost()
	{
		if (!isset($_POST['data'])) exit('--');        

        //Se não for JSON...
        $rec = json_decode($_POST['data']);
        if (!is_object($rec)) exit('---');

    //AES
        if (isset($rec->id)) {

        	$user = User::this()->getById($rec->id);

            $this->key = isset($user['token']) ? $user['token'] : false; //(new Lib\User)->getUserKey($rec->id);
            if ($this->key === false) return false;

            if(is_object($dec = json_decode(Aes::dec($rec->enc, $this->key)))){
            	$dec->id = isset($rec->id) ? $rec->id : 0;

            	return $dec;
            }
    //RSA 
        } elseif(isset($rec->enc)){
            $dec = base64_decode($rec->enc);
            $prv = openssl_pkey_get_private(file_get_contents(_CONFIG.'Key/private.key'));

            if(openssl_private_decrypt($dec, $dec, $prv)){
                if(is_object($dec = json_decode($dec))){
            		return $dec;
            	}
        	}            
        }
        return $rec;
	}

	/** Envia dados criptografados para o browser
     *
     *
     */
    final function sendData($dt) 
    {
        //Encriptando e enviando ...
        exit(Aes::enc(json_encode($dt), User::this()->get('token')));
    }


	private function sendHTML($view = 'form', $jsvar = null)
	{
		$html = new Html;
		$html->body(__DIR__.'/'.$view)
			 ->insertStyles($this->styles)
			 ->insertScripts($this->scripts)
			 ->val('title', 'BilLAB :: testes');

		if($jsvar !== null) $html->jsvar('PUBKEY', $jsvar);

		$html->render()
		     ->send();
	}

}