<?php


namespace Palopoli\PaloSystem\Controller\Front;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;

class HomeController extends ContainerAware
{


    public function IndexAction ()
    {

        $banner = $this->get('db')->fetchAll("SELECT * FROM banner where enabled = 1 and type=1 order by id ");        
        $banner_mobile = $this->get('db')->fetchAll("SELECT * FROM banner where enabled = 2 and type=1 order by id ");        

        $empreendimentos = $this->get('db')->fetchAll("SELECT e.*,oe.titulo as titulo_obra, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 order by e.`order`");        

        
        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/index.twig', array(
            'banner' => $banner,
            'banner_mobile' => $banner_mobile,
            'empreendimentos' => $empreendimentos,

        ));
    }


    
    public function MobileMarketingAction ()
    {

        $apps = $this->get('db')->fetchAll("SELECT * FROM apps_type where enabled = 1 order by id ");        
        $impulsos = $this->get('db')->fetchAll("SELECT * FROM impulsos_type where enabled = 1 order by id ");        
        $diversifique = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 4 order by id ");        
        $reter = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 5 order by id ");     
        
        $depoimentos = $this->get('db')->fetchAll("SELECT * FROM depoimentos_type where enabled = 1 order by id ");     
        
        foreach ($depoimentos as $key => $item) {            
            $depoimentos[$key]["body"] = preg_replace('#<p.*?>#is', '', $depoimentos[$key]["body"]);
            $depoimentos[$key]["body"] = preg_replace('#</p>#is', '', $depoimentos[$key]["body"]);
        }
        
        
        
        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/mobile_marketing.twig', array(
            'apps' => $apps,
            'impulsos' => $impulsos,
            'diversifique' => $diversifique,
            'reter' => $reter,
            'depoimentos' => $depoimentos,

        ));
    }

    public function HubParceirosAction ()
    {

        $expanda = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 6 order by id ");
        $diversificacao = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 7 order by id ");

        $depoimentos = $this->get('db')->fetchAll("SELECT * FROM depoimentos_type where enabled = 1 order by id ");     
        
        foreach ($depoimentos as $key => $item) {            
            $depoimentos[$key]["body"] = preg_replace('#<p.*?>#is', '', $depoimentos[$key]["body"]);
            $depoimentos[$key]["body"] = preg_replace('#</p>#is', '', $depoimentos[$key]["body"]);
        }

        $resultados = $this->get('db')->fetchAll("SELECT * FROM resultados_type where enabled = 1 order by id ");        

        foreach ($resultados as $key => $item) {            
            $resultados[$key]["body"] = preg_replace('#<p.*?>#is', '', $resultados[$key]["body"]);
            $resultados[$key]["body"] = preg_replace('#</p>#is', '', $resultados[$key]["body"]);
        }
        
        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/hub_parceiros.twig', array(
            'expanda' => $expanda,
            'diversificacao' => $diversificacao,
            'depoimentos' => $depoimentos,
            'resultados' => $resultados,
            

        ));
    }


    public function EmpresaAction ()
    {

        $sobre = $this->get('db')->fetchAssoc("SELECT * FROM institutional_type where id = 8 order by id ");
        $depoimento = $this->get('db')->fetchAssoc("SELECT * FROM institutional_type where id = 9 order by id ");
        $nossos_numeros = $this->get('db')->fetchAll("SELECT * FROM institutional where type = 1 and enabled = 1 order by id ");        
        $historia = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 10 order by id ");
        $valores = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 11 order by id ");
        $trabalhe = $this->get('db')->fetchAssoc("SELECT * FROM institutional_type where id = 9 order by id ");

        $depoimentos = $this->get('db')->fetchAll("SELECT * FROM depoimentos_type where enabled = 1 order by id ");     
        
        foreach ($depoimentos as $key => $item) {            
            $depoimentos[$key]["body"] = preg_replace('#<p.*?>#is', '', $depoimentos[$key]["body"]);
            $depoimentos[$key]["body"] = preg_replace('#</p>#is', '', $depoimentos[$key]["body"]);
        }

        $trabalhe = $this->get('db')->fetchAssoc("SELECT * FROM institutional_type where id = 9 order by id ");
        

        
        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/empresa.twig', array(
            'sobre' => $sobre,
            'depoimento' => $depoimento,
            'nossos_numeros' => $nossos_numeros,
            'historia' => $historia,
            'valores' => $valores,            
            'depoimentos' => $depoimentos,
            'trabalhe' => $trabalhe,
            
            

        ));
    }


    public function PublisherAction ()
    {

        $parceiros = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 13 order by id ");
        $expanda = $this->get('db')->fetchAll("SELECT * FROM institutional where enabled = 1 and type = 6 order by id ");
        $marcas = $this->get('db')->fetchAll("SELECT * FROM marcas_type where enabled = 1 order by id ");        

        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/publisher.twig', array(
            'parceiros' => $parceiros,
            'expanda' => $expanda,
            'marcas' => $marcas,
            

        ));
    }


    public function FaleConoscoAction ()
    {

        

        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/contato.twig', array(
            
            

        ));
    }



    public function FaleConoscoSendAction(Request $request){
        if("POST" == $request->getMethod()){
  
          $data = $request->request->all();
          unset($data['send']);
  
          //validar envio do form
          if(!in_array('', $data) && count($data) > 0){
            $message = \Swift_Message::newInstance();
            $message->setSubject('Contato via site');
            $message->setFrom(array($this->get('swiftmailer.options')['from'] => 'Contato Booster'));
            $message->setTo(array("pedro.palopoli@hotmail.com"));            
            //$message->setReplyTo(array($data['email'] => $data['nome']));
            $message->setBody($this->render('/emails/fale_conosco.twig', array(
              'data' => $data,
            )), 'text/html');
  
            if (!$this->get('mailer')->send($message)) {
              //return $this->json(['message' => 'Erro ao enviar mensagem, tente novamente!', 'type' => 'error', 'title' => 'Erro'], 400);
              $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));
            } else {
              //return $this->json(['message' => 'Mensagem enviada com sucesso.', 'type' => 'success', 'title' => 'Enviado'], 201);
              $this->flashMessage()->add('success', array('message' => 'Email enviado com sucesso.'));
            }
          } else {
            //return $this->json(['message' => 'Por favor, preencha todos os campos!', 'type' => 'warning', 'title' => 'Atenção'], 406);
            $this->flashMessage()->add('danger', array('message' => 'Por favor, preencha todos os campos!'));
          }
        }
        //return $this->json([]);
        // return $this->render('/front/contato.twig', array(
        //     // 'unidades' => $unidades,
        //     // 'areas' => $areas,
        //     // 'vagas' => $vagas,
        // ));

        return $this->redirect('web_home');
      }

}
