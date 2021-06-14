<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Config;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    function home(){
        return view('dashboard.home');
    }
    function login(Request $request){
        if ($request->isMethod('post')) {
            $post = $request->all();
            $usuario = Usuario::where('login',$post['usuario'])->where('senha',md5($post['senha']))->first();
            if($usuario){
                Session::put('logado',['id'=>$usuario->id,'tipo'=>$usuario->usuariotipos_id,'nome'=>$usuario->nome,'filiacao'=>$usuario->filiacao]);
                $home = '/';
                if($usuario->usuariotipos_id == 3){
                    $home = '/';//inicial para cozinha
                }elseif($usuario->usuariotipos_id == 4){
                    $home = '/atendimento';//inicial para atendentes
                }elseif($usuario->usuariotipos_id == 5){
                    $home = '/caixa';//inicial para atendentes
                }
                return redirect($home);
            }else{
                session()->flash('error', 'Usuário não encontrado.');
            }
        }
        return view('dashboard.login');
    }
    function senha(Request $request){
        session()->flash('info', 'Uma nova senha foi enviada ao email.');
        return redirect('/login');
    }
    function logout(){
        Session::put('logado',null);
        return redirect('/login');
    }
    //apenas tela de aviso
    function acessoNegado(){
        return view('dashboard.acesso-negado');
    }
    //tela de edicao de variaveis configuraveis
    function configs(Request $request){
        if(\Helper::loginTemNivel([1,2])){
            if ($request->isMethod('post')) {
                try{
                    $post = $request->all();
                    foreach($post['cfg'] as $k=>$v){
                        Config::where('chave',$k)->update(['valor'=>$v]);
                    }
                    session()->flash('info', 'Configurações atualizadas.');
                } catch (Exception $ex) {}
                
            }
            return view('dashboard.configs',['rows'=> Config::all()]);
        }else{
            return $this->acessoNegado();
        }
    }
}
