<?php

/*
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>
 */

// Routes Website
$route = $app['controllers_factory'];

// Rotas Painel
$route->mount(sprintf('/%s', $app['security_path']), require(__DIR__.'/routes_security.php'));

// Renderizar imagens
$route->get('img/{path}/{imagem}', function (Silex\Application $app, Symfony\Component\HttpFoundation\Request $request, $path, $imagem) {
    return $app['glide']->outputImage(sprintf('%s/%s', $path, $imagem), $request->query->all());
})->bind('imagem');

 $route->get('/', 'Front\Home::Index')->bind('web_home');
 $route->get('/sobre-nos/', 'Front\Home::Sobre')->bind('web_sobre');
 $route->get('/contato/', 'Front\Home::Contato')->bind('web_contato');
 $route->get('/noticias/', 'Front\Home::TodasNoticias')->bind('web_todas_noticias');
 $route->get('/noticias/{url_blog}/', 'Front\Home::InternaNoticia')->bind('web_noticia_interna');
 $route->get('/empreendimentos/', 'Front\Home::TodosEmpreendimentos')->bind('web_todos_empreendimentos');
 $route->get('/empreendimentos/{url_categoria}/', 'Front\Home::TodosEmpreendimentosCategoria')->bind('web_todos_empreendimentos_categoria');
 $route->get('/empreendimento/{url_empreendimento}/', 'Front\Home::InternaEmpreendimento')->bind('web_interna_empreendimento');
// $route->get('mobile-marketing', 'Front\Home::MobileMarketing')->bind('web_mobile_marketing');
// $route->get('hub-parceiros', 'Front\Home::HubParceiros')->bind('web_hub_parceiros');
// $route->get('empresa', 'Front\Home::Empresa')->bind('web_empresa');
// $route->get('publisher', 'Front\Home::Publisher')->bind('web_publisher');

// $route->get('contato', 'Front\Home::FaleConosco')->bind('web_contato');
// $route->post('contato-send/', 'Front\Home::FaleConoscoSend')->bind('web_contato_send');

// $route->get('blog', 'Front\Blog::IndexAction')->bind('web_blog');
// $route->get('blog/{url_category}', 'Front\Blog::IndexCategoryAction')->bind('web_blog_category');
// $route->post('blog/resultado', 'Front\Blog::IndexResultadoAction')->bind('web_blog_resultado');
// $route->get('blog/{url_category}/{url_blog}', 'Front\Blog::IndexBlogAction')->bind('web_blog_interna');
// //$route->get('/blog', 'Front\Home::Index')->bind('web_home');

// $route->post('blog/newslatter', 'Front\Blog::NewslatterAction')->bind('web_newslatter_cadastros');

return $route;
