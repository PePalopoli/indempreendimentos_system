<?php

/**
 *  (c) Pedro Palópoli <pedro.palopoli@hotmail.com>.
 */

// Routes Security
$security = $app['controllers_factory'];

$security->get('/', 'Security\Security::index'); // Dashboard
$security->get('/login', 'Security\Security::login'); // Login

// Roles
$security->match('roles', 'Security\Role::index')->method('GET|POST')->bind('s_role');
$security->match('roles/create', 'Security\Role::create')->method('GET|POST')->bind('s_role_create');
$security->match('roles/edit/{id}', 'Security\Role::edit')->method('GET|POST')->bind('s_role_edit');
$security->delete('roles/delete/{id}', 'Security\Role::delete')->bind('s_role_delete');

// Users
$security->match('user', 'Security\User::index')->method('GET|POST')->bind('s_user');
$security->match('user/create', 'Security\User::create')->method('GET|POST')->bind('s_user_create');
$security->match('user/edit/{id}', 'Security\User::edit')->method('GET|POST')->bind('s_user_edit');
$security->delete('user/delete/{id}', 'Security\User::delete')->bind('s_user_delete');

// Access
$security->match('access', 'Security\Access::index')->method('GET|POST')->bind('s_access');
$security->put('access/order', 'Security\Access::order')->bind('s_access_order');
$security->match('access/create', 'Security\Access::create')->method('GET|POST')->bind('s_access_create');
$security->match('access/edit/{id}', 'Security\Access::edit')->method('GET|POST')->bind('s_access_edit');
$security->delete('access/delete/{id}', 'Security\Access::delete')->bind('s_access_delete');

// Banner Type
$security->match('banner', 'Security\BannerType::index')->method('GET|POST')->bind('s_banner_type');
$security->match('banner/create', 'Security\BannerType::create')->method('GET|POST')->bind('s_banner_type_create');
$security->match('banner/edit/{id}', 'Security\BannerType::edit')->method('GET|POST')->bind('s_banner_type_edit');
$security->delete('banner/delete/{id}', 'Security\BannerType::delete')->bind('s_banner_type_delete');

$app['converter.banner_type'] = $app->share(function (Silex\Application $app) {
    return new Palopoli\PaloSystem\Service\BannerTypeConverter($app);
});

// Banner
$security->match('banner/{banner_type}/list', 'Security\Banner::index')->method('GET|POST')->convert('banner_type', 'converter.banner_type:convert')->bind('s_banner');
$security->put('banner/{banner_type}/order', 'Security\Banner::order')->convert('banner_type', 'converter.banner_type:convert')->bind('s_banner_order');
$security->match('banner/{banner_type}/list/create', 'Security\Banner::create')->method('GET|POST')->convert('banner_type', 'converter.banner_type:convert')->bind('s_banner_create');
$security->match('banner/{banner_type}/list/edit/{id}', 'Security\Banner::edit')->method('GET|POST')->convert('banner_type', 'converter.banner_type:convert')->bind('s_banner_edit');
$security->delete('banner/{banner_type}/list/delete/{id}', 'Security\Banner::delete')->convert('banner_type', 'converter.banner_type:convert')->bind('s_banner_delete');

// Institutional Type
$security->match('institutional', 'Security\InstitutionalType::index')->method('GET|POST')->bind('s_institutional_type');
$security->match('institutional/create', 'Security\InstitutionalType::create')->method('GET|POST')->bind('s_institutional_type_create');
$security->match('institutional/edit/{id}', 'Security\InstitutionalType::edit')->method('GET|POST')->bind('s_institutional_type_edit');
$security->delete('institutional/delete/{id}', 'Security\InstitutionalType::delete')->bind('s_institutional_type_delete');

$app['converter.institutional_type'] = $app->share(function (Silex\Application $app) {
    return new Palopoli\PaloSystem\Service\InstitutionalTypeConverter($app);
});

// Institutional
$security->match('institutional/{institutional_type}', 'Security\Institutional::index')->method('GET|POST')->convert('institutional_type', 'converter.institutional_type:convert')->bind('s_institutional');
$security->match('institutional/{institutional_type}/create', 'Security\Institutional::create')->method('GET|POST')->convert('institutional_type', 'converter.institutional_type:convert')->bind('s_institutional_create');
$security->match('institutional/{institutional_type}/edit/{id}', 'Security\Institutional::edit')->method('GET|POST')->convert('institutional_type', 'converter.institutional_type:convert')->bind('s_institutional_edit');
$security->delete('institutional/{institutional_type}/delete/{id}', 'Security\Institutional::delete')->convert('institutional_type', 'converter.institutional_type:convert')->bind('s_institutional_delete');

// SEO
$security->match('seo', 'Security\Seo::index')->method('GET|POST')->bind('s_seo');
$security->put('seo/order', 'Security\Seo::order')->bind('s_seo_order');
$security->match('seo/create', 'Security\Seo::create')->method('GET|POST')->bind('s_seo_create');
$security->match('seo/edit/{id}', 'Security\Seo::edit')->method('GET|POST')->bind('s_seo_edit');
$security->delete('seo/delete/{id}', 'Security\Seo::delete')->bind('s_seo_delete');


// Marcas Type
$security->match('marcas', 'Security\MarcasType::index')->method('GET|POST')->bind('s_marcas_type');
$security->match('marcas/create', 'Security\MarcasType::create')->method('GET|POST')->bind('s_marcas_type_create');
$security->match('marcas/edit/{id}', 'Security\MarcasType::edit')->method('GET|POST')->bind('s_marcas_type_edit');
$security->delete('marcas/delete/{id}', 'Security\MarcasType::delete')->bind('s_marcas_type_delete');

// Servicos Type
$security->match('servicos', 'Security\ServicosType::index')->method('GET|POST')->bind('s_servicos_type');
$security->match('servicos/create', 'Security\ServicosType::create')->method('GET|POST')->bind('s_servicos_type_create');
$security->match('servicos/edit/{id}', 'Security\ServicosType::edit')->method('GET|POST')->bind('s_servicos_type_edit');
$security->delete('servicos/delete/{id}', 'Security\ServicosType::delete')->bind('s_servicos_type_delete');

// Resultados Type
$security->match('resultados', 'Security\ResultadosType::index')->method('GET|POST')->bind('s_resultados_type');
$security->match('resultados/create', 'Security\ResultadosType::create')->method('GET|POST')->bind('s_resultados_type_create');
$security->match('resultados/edit/{id}', 'Security\ResultadosType::edit')->method('GET|POST')->bind('s_resultados_type_edit');
$security->delete('resultados/delete/{id}', 'Security\ResultadosType::delete')->bind('s_resultados_type_delete');

// Depoimentos
$security->match('depoimentos', 'Security\Depoimentos::index')->method('GET|POST')->bind('s_depoimentos');
$security->put('depoimentos/order', 'Security\Depoimentos::order')->bind('s_depoimentos_order');
$security->match('depoimentos/create', 'Security\Depoimentos::create')->method('GET|POST')->bind('s_depoimentos_create');
$security->match('depoimentos/edit/{id}', 'Security\Depoimentos::edit')->method('GET|POST')->bind('s_depoimentos_edit');
$security->delete('depoimentos/delete/{id}', 'Security\Depoimentos::delete')->bind('s_depoimentos_delete');

// apps Type
$security->match('apps', 'Security\AppsType::index')->method('GET|POST')->bind('s_apps_type');
$security->match('apps/create', 'Security\AppsType::create')->method('GET|POST')->bind('s_apps_type_create');
$security->match('apps/edit/{id}', 'Security\AppsType::edit')->method('GET|POST')->bind('s_apps_type_edit');
$security->delete('apps/delete/{id}', 'Security\AppsType::delete')->bind('s_apps_type_delete');

// Impulsos Type
$security->match('impulsos', 'Security\ImpulsosType::index')->method('GET|POST')->bind('s_impulsos_type');
$security->match('impulsos/create', 'Security\ImpulsosType::create')->method('GET|POST')->bind('s_impulsos_type_create');
$security->match('impulsos/edit/{id}', 'Security\ImpulsosType::edit')->method('GET|POST')->bind('s_impulsos_type_edit');
$security->delete('impulsos/delete/{id}', 'Security\ImpulsosType::delete')->bind('s_impulsos_type_delete');



// Institutional Type
$security->match('blog', 'Security\BlogType::index')->method('GET|POST')->bind('s_blog_type');
$security->match('blog/create', 'Security\BlogType::create')->method('GET|POST')->bind('s_blog_type_create');
$security->match('blog/edit/{id}', 'Security\BlogType::edit')->method('GET|POST')->bind('s_blog_type_edit');
$security->delete('blog/delete/{id}', 'Security\BlogType::delete')->bind('s_blog_type_delete');

$app['converter.blog_type'] = $app->share(function (Silex\Application $app) {
    return new Palopoli\PaloSystem\Service\BlogTypeConverter($app);
});

// Institutional
$security->match('blog/{blog_type}', 'Security\Blog::index')->method('GET|POST')->convert('blog_type', 'converter.blog_type:convert')->bind('s_blog');
$security->match('blog/{blog_type}/create', 'Security\Blog::create')->method('GET|POST')->convert('blog_type', 'converter.blog_type:convert')->bind('s_blog_create');
$security->match('blog/{blog_type}/edit/{id}', 'Security\Blog::edit')->method('GET|POST')->convert('blog_type', 'converter.blog_type:convert')->bind('s_blog_edit');
$security->delete('blog/{blog_type}/delete/{id}', 'Security\Blog::delete')->convert('blog_type', 'converter.blog_type:convert')->bind('s_blog_delete');

// Blog Categoria
$security->match('blog-categoria', 'Security\BlogCategoria::index')->method('GET|POST')->bind('s_blog_categoria');
$security->put('blog-categoria/order', 'Security\BlogCategoria::order')->bind('s_blog_categoria_order');
$security->match('blog-categoria/create', 'Security\BlogCategoria::create')->method('GET|POST')->bind('s_blog_categoria_create');
$security->match('blog-categoria/edit/{id}', 'Security\BlogCategoria::edit')->method('GET|POST')->bind('s_blog_categoria_edit');
$security->delete('blog-categoria/delete/{id}', 'Security\BlogCategoria::delete')->bind('s_blog_categoria_delete');

// Blog Post
$security->match('blog-post', 'Security\BlogPost::index')->method('GET|POST')->bind('s_blog_post');
$security->match('blog-post/create', 'Security\BlogPost::create')->method('GET|POST')->bind('s_blog_post_create');
$security->match('blog-post/edit/{id}', 'Security\BlogPost::edit')->method('GET|POST')->bind('s_blog_post_edit');
$security->delete('blog-post/delete/{id}', 'Security\BlogPost::delete')->bind('s_blog_post_delete');



// Obra Etapas
$security->match('obra-etapas', 'Security\ObraEtapas::index')->method('GET|POST')->bind('s_obra_etapas');
$security->put('obra-etapas/order', 'Security\ObraEtapas::order')->bind('s_obra_etapas_order');
$security->match('obra-etapas/create', 'Security\ObraEtapas::create')->method('GET|POST')->bind('s_obra_etapas_create');
$security->match('obra-etapas/edit/{id}', 'Security\ObraEtapas::edit')->method('GET|POST')->bind('s_obra_etapas_edit');
$security->delete('obra-etapas/delete/{id}', 'Security\ObraEtapas::delete')->bind('s_obra_etapas_delete');

// Empreendimentos
$security->match('empreendimentos-sistema', 'Security\Empreendimentos::index')->method('GET|POST')->bind('s_empreendimentos');
$security->put('empreendimentos-sistema/order', 'Security\Empreendimentos::order')->bind('s_empreendimentos_order');
$security->match('empreendimentos-sistema/create', 'Security\Empreendimentos::create')->method('GET|POST')->bind('s_empreendimentos_create');
$security->match('empreendimentos-sistema/edit/{id}', 'Security\Empreendimentos::edit')->method('GET|POST')->bind('s_empreendimentos_edit');
$security->delete('empreendimentos-sistema/delete/{id}', 'Security\Empreendimentos::delete')->bind('s_empreendimentos_delete');
$security->post('empreendimentos-sistema/galeria/remove', 'Security\Empreendimentos::removeGaleriaImage')->bind('s_empreendimentos_remove_galeria_image');
$security->post('empreendimentos-sistema/beneficio/adicionar', 'Security\Empreendimentos::adicionarBeneficio')->bind('s_empreendimentos_adicionar_beneficio');
$security->post('empreendimentos-sistema/beneficio/remover', 'Security\Empreendimentos::removerBeneficio')->bind('s_empreendimentos_remover_beneficio');
$security->post('empreendimentos-sistema/tour-botao/adicionar', 'Security\Empreendimentos::adicionarBotaoTour')->bind('s_empreendimentos_adicionar_botao_tour');
$security->post('empreendimentos-sistema/tour-botao/remover', 'Security\Empreendimentos::removerBotaoTour')->bind('s_empreendimentos_remover_botao_tour');

// Galeria de Empreendimentos
$security->match('empreendimentos/{empreendimento_id}/galeria', 'Security\EmpreendimentosGaleria::index')->method('GET|POST')->bind('s_empreendimentos_galeria');
$security->put('empreendimentos/{empreendimento_id}/galeria/order', 'Security\EmpreendimentosGaleria::order')->bind('s_empreendimentos_galeria_order');
$security->match('empreendimentos/{empreendimento_id}/galeria/create', 'Security\EmpreendimentosGaleria::create')->method('GET|POST')->bind('s_empreendimentos_galeria_create');
$security->match('empreendimentos/{empreendimento_id}/galeria/edit/{id}', 'Security\EmpreendimentosGaleria::edit')->method('GET|POST')->bind('s_empreendimentos_galeria_edit');
$security->delete('empreendimentos/{empreendimento_id}/galeria/delete/{id}', 'Security\EmpreendimentosGaleria::delete')->bind('s_empreendimentos_galeria_delete');

// Benefícios de Empreendimentos
$security->match('beneficios-empreendimentos', 'Security\BeneficiosEmpreendimentos::index')->method('GET|POST')->bind('s_beneficios_empreendimentos');
$security->put('beneficios-empreendimentos/order', 'Security\BeneficiosEmpreendimentos::order')->bind('s_beneficios_empreendimentos_order');
$security->match('beneficios-empreendimentos/create', 'Security\BeneficiosEmpreendimentos::create')->method('GET|POST')->bind('s_beneficios_empreendimentos_create');
$security->match('beneficios-empreendimentos/edit/{id}', 'Security\BeneficiosEmpreendimentos::edit')->method('GET|POST')->bind('s_beneficios_empreendimentos_edit');
$security->delete('beneficios-empreendimentos/delete/{id}', 'Security\BeneficiosEmpreendimentos::delete')->bind('s_beneficios_empreendimentos_delete');

return $security;
