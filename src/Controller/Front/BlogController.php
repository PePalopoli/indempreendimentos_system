<?php


namespace Palopoli\PaloSystem\Controller\Front;

use Palopoli\PaloSystem\Controller\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\User\User;

class BlogController extends ContainerAware
{

    

    private function getBlogCategory ($url_category)
    {
        $blog_category = $this->get('db')->fetchAssoc('SELECT * FROM `blog_type` WHERE `enabled` = 1 AND `url` = ? ORDER BY `id` DESC', array($url_category));
        $this->get('db')->close();
        return $blog_category;
    }
    private function getAllBlogCategory(){
        $blog_category = $this->get('db')->fetchAll('SELECT * FROM `blog_type` WHERE `enabled` = 1 ORDER BY `id` asc');
        $this->get('db')->close();
        return $blog_category;
    }

    private function getBlog ($url_blog)
    {
        $blog = $this->get('db')->fetchAssoc('SELECT * FROM `blog` WHERE `enabled` = 1 AND `url` = ? ORDER BY `id` DESC', array($url_blog));
        $this->get('db')->close();
        return $blog;
    }

    private function lastBlog(){
        $blog = $this->get('db')->fetchAssoc('SELECT b.*,bt.title as categoria, bt.url as url_category FROM blog b 
                                                INNER JOIN blog_type bt on bt.id = b.type
                                                WHERE b.enabled = 1 and bt.enabled=1 ORDER BY b.id DESC LIMIT 1');
        $this->get('db')->close();
        return $blog;
    }

    private function getAllBlog($categoria = null){

        if($categoria){
            $where = 'AND bt.url = ?';
            $params = array($categoria);
        }else{
            $where = '';
            $params = array();
        }


        $blog = $this->get('db')->fetchAll('SELECT b.*,bt.title as categoria, bt.url as url_category FROM blog b 
                                                INNER JOIN blog_type bt on bt.id = b.type
                                                WHERE b.enabled = 1 and bt.enabled=1 '.$where.' ORDER BY b.id DESC', $params);
        $this->get('db')->close();
        return $blog;
    }



    public function IndexAction ()
    {
        //dd($baixar_facil);

        return $this->render('/front/blog.twig', array(
            'blog_type' => $this->getAllBlogCategory(),
            'blog' => $this->getAllBlog(),
            'last_blog' => $this->lastBlog(),
            


        ));
    }
    public function IndexCategoryAction ($url_category)
    {
        //dd($baixar_facil);

        return $this->render('/front/blog.twig', array(
            'blog_type' => $this->getAllBlogCategory(),
            'blog' => $this->getAllBlog($url_category),
            'last_blog' => $this->lastBlog(),
            


        ));
    }
    public function IndexResultadoAction (Request $request)
    {
        $blogSearch = $request->get('blogSearch');
        $blog = $this->get('db')->fetchAll('SELECT b.*,bt.title as categoria, bt.url as url_category FROM blog b 
                                                INNER JOIN blog_type bt on bt.id = b.type
                                                WHERE b.enabled = 1 and bt.enabled=1 and ( b.title like ? or b.subtitle like ?
                                                or b.body like ? or b.url like ? or bt.url like ? or bt.title like ?)
                                                ORDER BY b.id DESC', array('%'.$blogSearch.'%', '%'.$blogSearch.'%', '%'.$blogSearch.'%', '%'.$blogSearch.'%', '%'.$blogSearch.'%', '%'.$blogSearch.'%'));
        $this->get('db')->close();

        return $this->render('/front/blog.twig', array(
            'blog_type' => $this->getAllBlogCategory(),
            'blog' => $blog,
            'last_blog' => $this->lastBlog(),
        ));
    }

    public function IndexBlogAction ($url_blog, $url_category)
    {
        $blog_interna = $this->getBlog($url_blog);
        $blog_category = $this->getBlogCategory($url_category);
        
        return $this->render('/front/blog_inter.twig', array(
            'blog_interna' => $blog_interna,
            'blog_type' => $this->getAllBlogCategory(),
            'blog_category' => $blog_category,
            'blog' => $this->getAllBlog(),
            'last_blog' => $this->lastBlog(),
        ));
    }


    public function NewslatterAction (Request $request)
    {
        $email = $request->get('iEmail');
        //dd($email);
        try {
            $insert_query = 'INSERT INTO `newslatter_type` (`email`, `created_at`) VALUES (?, NOW())';
            $this->db()->executeUpdate($insert_query, array($email));
            
            $this->flashMessage()->add('success', array('message' => 'Newsletter cadastrado com sucesso.'));

            // return $this->redirect('s_blog', array('blog_type' => $blog_type['id']));
        } catch (UniqueConstraintViolationException $e) {
            //dd("certo");
            $this->flashMessage()->add('danger', array('message' => 'Não foi possível cadastrar o email.'));
        }
        $this->get('db')->close();

        //return $this->redirect($this->generateUrl('web_blog'));
        return $this->redirect('web_blog');

    }


    
    

}
