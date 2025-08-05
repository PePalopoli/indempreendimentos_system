<?php


namespace Palopoli\PaloSystem\Controller\Front;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\User;

class HomeController extends ContainerAware
{


    private function GetEmpreendimentos()
    {
        return $this->get('db')->fetchAll("SELECT e.*,oe.titulo as titulo_obra, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 order by e.`order`");
    }

    public function IndexAction()
    {

        $banner = $this->get('db')->fetchAll("SELECT * FROM banner where enabled = 1 and type=1 order by id ");
        $banner_mobile = $this->get('db')->fetchAll("SELECT * FROM banner where enabled = 2 and type=1 order by id ");

        //$empreendimentos = $this->get('db')->fetchAll("SELECT e.*,oe.titulo as titulo_obra, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 order by e.`order`");        
        $depoimentos = $this->get('db')->fetchAll("SELECT * FROM depoimentos where enabled = 1 order by rand() limit 3 ");

        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/index.twig', array(
            'banner' => $banner,
            'banner_mobile' => $banner_mobile,
            'empreendimentos' => $this->GetEmpreendimentos(),
            'depoimentos' => $depoimentos,

        ));
    }


    public function SobreAction()
    {

        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/sobre_nos.twig', array());
    }

    public function ContatoAction()
    {

        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/contato.twig', array());
    }

    public function TodasNoticiasAction()
    {
        $noticias = $this->get('db')->fetchAll("SELECT bp.*, bc.titulo as categoria_titulo FROM blog_post bp inner join blog_categoria bc on bc.id = bp.categoria_id where bp.enabled = 1 order by id desc");
        $noticias_destaque = $this->get('db')->fetchAll("SELECT bp.*, bc.titulo as categoria_titulo FROM blog_post bp inner join blog_categoria bc on bc.id = bp.categoria_id where bp.enabled = 1 and bp.destaque = 1 order by id desc");
        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/todas_noticias.twig', array(
            'noticias' => $noticias,
            'noticias_destaque' => $noticias_destaque,
        ));
    }

    public function InternaNoticiaAction($url_blog)
    {
        $noticia = $this->get('db')->fetchAssoc("SELECT bp.*, bc.titulo as categoria_titulo FROM blog_post bp inner join blog_categoria bc on bc.id = bp.categoria_id where bp.enabled = 1 and bp.slug = ?", array($url_blog));
        $noticias = $this->get('db')->fetchAll("SELECT bp.*, bc.titulo as categoria_titulo FROM blog_post bp inner join blog_categoria bc on bc.id = bp.categoria_id where bp.enabled = 1 order by id desc");
        $this->get('db')->close();
        //dd($baixar_facil);
        $this->get('seo')->setTitle($noticia['meta_title']);
        $this->get('seo')->setDescription($noticia['meta_description']);
        $this->get('seo')->setImage($noticia['imagem_capa']);
        $this->get('seo')->setTwitterCard('summary_large_image');
        $this->get('seo')->setTwitterImage($noticia['imagem_capa']);
        $this->get('seo')->setKeywords($noticia['meta_keywords']);





        //dd($this->get('seo')->all());






        return $this->render('/front/interna_noticia.twig', array(
            'noticia' => $noticia,
            'noticias' => $noticias,
            'seo' => $this->get('seo')->all(),
        ));
    }



    public function TodosEmpreendimentosAction()
    {
        $empreendimentos = $this->get('db')->fetchAll("SELECT e.*,oe.titulo as titulo_etapa, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 order by e.`order`");
        $etapas = $this->get('db')->fetchAll("SELECT * FROM obra_etapas where enabled = 1 order by id asc");
        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/todos_empreendimentos.twig', array(
            'empreendimentos' => $empreendimentos,
            'etapas' => $etapas,
        ));
    }

    public function TodosEmpreendimentosCategoriaAction($url_categoria)
    {
        $empreendimentos = $this->get('db')->fetchAll("SELECT e.*,oe.titulo as titulo_etapa, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 and oe.slug = ? order by e.`order`", array($url_categoria));
        $etapas = $this->get('db')->fetchAll("SELECT * FROM obra_etapas where enabled = 1 order by id asc");
        $this->get('db')->close();
        //dd($baixar_facil);

        return $this->render('/front/todos_empreendimentos.twig', array(
            'empreendimentos' => $empreendimentos,
            'etapas' => $etapas,
        ));
    }

    public function InternaEmpreendimentoAction($url_empreendimento)
    {
        $empreendimento = $this->get('db')->fetchAssoc("SELECT e.*,oe.titulo as titulo_etapa, oe.cor_hex FROM empreendimentos e inner join obra_etapas oe on oe.id = e.etapa_id where e.enabled = 1 and e.slug = ?", array($url_empreendimento));
        $etapas = $this->get('db')->fetchAll("SELECT * FROM obra_etapas where enabled = 1 order by id asc");
        $galeria = $this->get('db')->fetchAll("SELECT * FROM empreendimentos_galeria where empreendimento_id = ?", array($empreendimento['id']));
        $empreendimentos_beneficios = $this->get('db')->fetchAll("SELECT eb.*,ebt.svg_code FROM empreendimentos_beneficios eb inner join beneficios_empreendimentos ebt on ebt.id = eb.beneficio_id where empreendimento_id = ? order by eb.order ", array($empreendimento['id']));
        $empreendimentos_tour_botoes = $this->get('db')->fetchAll("SELECT * FROM empreendimentos_tour_botoes where empreendimento_id = ?", array($empreendimento['id']));
        $empreendimentos_plantas = $this->get('db')->fetchAll("SELECT * FROM empreendimentos_plantas where empreendimento_id = ?", array($empreendimento['id']));

        $this->get('seo')->setTitle($empreendimento['meta_title']);
        $this->get('seo')->setDescription($empreendimento['meta_description']);
        $this->get('seo')->setImage($empreendimento['img_capa']);
        $this->get('seo')->setTwitterCard('summary_large_image');
        $this->get('seo')->setTwitterImage($empreendimento['img_capa']);
        $this->get('seo')->setKeywords($empreendimento['meta_keywords']);
        $this->get('db')->close();

        //dd($galeria);

        return $this->render('/front/interna_empreendimentos.twig', array(
            'empreendimento' => $empreendimento,
            'etapas' => $etapas,
            'galeria' => $galeria,
            'empreendimentos_beneficios' => $empreendimentos_beneficios,
            'empreendimentos_tour_botoes' => $empreendimentos_tour_botoes,
            'empreendimentos_plantas' => $empreendimentos_plantas,
            'lista_empreendimentos' => $this->GetEmpreendimentos(),
            'seo' => $this->get('seo')->all(),

        ));
    }





































    public function MobileMarketingAction()
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

    public function HubParceirosAction()
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


    public function EmpresaAction()
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


    public function PublisherAction()
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


    public function FaleConoscoAction()
    {



        $this->get('db')->close();
        //dd($impulsos);

        return $this->render('/front/contato.twig', array());
    }



    public function FaleConoscoSendAction(Request $request)
    {
        return $this->processFormSubmission($request, 'contato', 'web_home');
    }

    /**
     * Método unificado para processar formulários com captação de leads
     */
    public function ContatoSendAction(Request $request, $formType = 'contato', $redirectRoute = null)
    {
        return $this->processFormSubmission($request, $formType, $redirectRoute);
    }

    /**
     * Método unificado com proteções robustas e captação de leads
     */
    private function processFormSubmission(Request $request, $formType = 'contato', $redirectRoute = null)
    {
        // Validação 1: Verificar se é POST
        if ("POST" !== $request->getMethod()) {
            $this->flashMessage()->add('danger', array('message' => 'Método de requisição inválido!'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        // Validação 2: Proteção contra User-Agent suspeitos
        $userAgent = $request->headers->get('User-Agent', '');
        $userAgentsSuspeitos = [
            'bot',
            'crawler',
            'spider',
            'scraper',
            'curl',
            'wget',
            'python',
            'java',
            'go-http',
            'postman',
            'insomnia',
            'httpclient',
            'okhttp',
            'apache-httpclient'
        ];

        foreach ($userAgentsSuspeitos as $suspeito) {
            if (stripos($userAgent, $suspeito) !== false) {
                error_log("Bot bloqueado: " . $userAgent . " - IP: " . $request->getClientIp());
                return $this->redirect($redirectRoute ?: 'web_home');
            }
        }

        // Validação 3: Rate Limiting por IP
        $clientIp = $request->getClientIp();
        $rateLimitFile = sys_get_temp_dir() . '/contact_rate_' . md5($clientIp) . '.tmp';

        // if (file_exists($rateLimitFile)) {
        //     $lastSubmission = (int)file_get_contents($rateLimitFile);
        //     if (time() - $lastSubmission < 60) {
        //         $this->flashMessage()->add('danger', array('message' => 'Muitas tentativas. Aguarde um minuto antes de tentar novamente.'));
        //         return $this->redirect($redirectRoute ?: 'web_home');
        //     }
        // }

        // Atualizar timestamp do rate limiting
        file_put_contents($rateLimitFile, time());

        // Validação 4: Verificar Referer
        $referer = $request->headers->get('Referer', '');
        $host = $request->getHost();

        if (empty($referer) || strpos($referer, $host) === false) {
            $this->flashMessage()->add('danger', array('message' => 'Acesso negado: origem inválida.'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        // Validação 5: Verificar CSRF Token (usando a mesma lógica do formatCSRF)
        $secret = 'e3b0c44298fc1c149afbboc8996fb92427aa45e4167b934ca495991b7852b855';
        $token = $request->request->get('csrf_token', '');

        if (empty($token)) {
            $this->flashMessage()->add('danger', array('message' => 'Token de segurança não encontrado!'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        $parts = explode(':', base64_decode($token));
        if (count($parts) !== 2) {
            $this->flashMessage()->add('danger', array('message' => 'Token de segurança inválido!'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        list($timestamp, $hash) = $parts;

        // Validação 6: Verificar assinatura do token
        if (!hash_equals(hash_hmac('sha256', $timestamp, $secret), $hash)) {
            $this->flashMessage()->add('danger', array('message' => 'Token de segurança inválido!'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        // Validação 7: Verificar expiração do token (2 minutos)
        if (time() - (int)$timestamp > 120) {
            $this->flashMessage()->add('danger', array('message' => 'Formulário expirado. Recarregue a página e tente novamente!'));
            return $this->redirect($redirectRoute ?: 'web_home');
        }

        // Pegar dados do formulário
        $data = $request->request->all();
        unset($data['send'], $data['csrf_token'], $data['form_type']);

        // Validar campos obrigatórios
        // if (in_array('', $data) || count($data) == 0) {
        //     $this->flashMessage()->add('danger', array('message' => 'Por favor, preencha todos os campos!'));
        //     return $this->redirect($redirectRoute ?: 'web_home');
        // }

        // **CAPTURAR LEAD**
        $leadId = null;
        try {
            $leadResult = $this->get('lead.service')->capturarLead($request, $data, $formType);
            $leadId = $leadResult['success'] ? $leadResult['lead_id'] : null;
            error_log("Lead capturado - ID: $leadId - FormType: $formType");
        } catch (\Exception $e) {
            error_log("ERRO ao capturar lead: " . $e->getMessage());
        }

        // **ENVIAR EMAIL**
        try {
            $message = \Swift_Message::newInstance();
            $message->setSubject($this->getEmailSubject($formType));
            $message->setFrom(array($this->get('swiftmailer.options')['from'] => 'Contato Indemepreendimentos'));
            $message->setTo(array("pedro.palopoli@hotmail.com"));

            // Reply-to se tiver email
            // if (!empty($data['iEmail'])) {
            //     $message->setReplyTo(array($data['iEmail'] => $data['iName'] ?? 'Visitante'));
            // }

            if (isset($data["iMsg"])) {
                $message->setBody($this->render('/emails/contato_home.twig', array(
                    'data' => $data,
                    'lead_id' => $leadId
                )), 'text/html');
            } else {
                //dd($message);
                // Renderizar template de email
                $message->setBody($this->render('/emails/empreendimento_interesse.twig', array(
                    'data' => $data,
                    'lead_id' => $leadId
                )), 'text/html');
                //dd($this->getEmailTemplate($formType));
            }



            $emailSent = $this->get('mailer')->send($message);

            // **Atualizar status do email no lead**
            if ($leadId) {
                $this->get('lead.service')->marcarEmailEnviado($leadId, $emailSent);
            }

            if ($emailSent) {
                $this->flashMessage()->add('success', array('message' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'));
            } else {
                $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));
            }
        } catch (\Exception $e) {
            error_log("Erro ao enviar email: " . $e->getMessage());
            $this->flashMessage()->add('danger', array('message' => 'Erro ao enviar mensagem, tente novamente!'));

            if ($leadId) {
                $this->get('lead.service')->marcarEmailEnviado($leadId, false);
            }
        }
        // Determinar para onde redirecionar
        $redirectUrl = $data['url_empreendimento_retorno'] ?? null;

        if (!empty($redirectUrl)) {
            //return $this->app->redirect($redirectUrl);                
            return new RedirectResponse($redirectUrl);
            //return $this->redirect($redirectUrl);            
        }

        // Fallback padrão
        return $this->redirect('web_home');
    }

    private function getRequiredFields($formType)
    {
        $fields = [
            'contato' => ['iName', 'iEmail', 'iTelefone', 'iMsg'],
            'empreendimento' => ['iName', 'iEmail', 'iWhatsapp'],
            'default' => ['iName', 'iEmail']
        ];
        return $fields[$formType] ?? $fields['default'];
    }

    private function getEmailSubject($formType)
    {
        $subjects = [
            'contato' => 'Contato via site',
            'empreendimento' => 'Interesse em Empreendimento',
            'default' => 'Contato via site'
        ];
        return $subjects[$formType] ?? $subjects['default'];
    }

    private function getEmailTemplate($formType)
    {
        $templates = [
            'contato' => '/emails/fale_conosco.twig',
            'empreendimento' => '/emails/empreendimento_interesse.twig',
            'default' => '/emails/fale_conosco.twig'
        ];
        return $templates[$formType] ?? $templates['default'];
    }
}
