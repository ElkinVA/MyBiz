<?php
class PageController extends Controller
{
    private $pageModel;

    public function __construct()
    {
        $this->pageModel = new PageModel();
    }

    public function show($slug)
    {
        try {
            $page = $this->pageModel->getBySlug($slug);
            
            if (!$page || !$page['is_active']) {
                $this->render('errors/404', [], 404);
                return;
            }

            // Получаем настройки для SEO
            $settingsModel = new SettingsModel();
            $siteSettings = $settingsModel->getAllGrouped();

            $data = [
                'page' => $page,
                'settings' => $siteSettings,
                'meta_title' => $page['meta_title'] ?: $page['title'],
                'meta_description' => $page['meta_description'] ?: $this->generateMetaDescription($page['content'])
            ];

            $this->render('pages/show', $data);
            
        } catch (Exception $e) {
            error_log("PageController error: " . $e->getMessage());
            $this->render('errors/500', [], 500);
        }
    }

    public function about()
    {
        $this->show('about');
    }

    public function contacts()
    {
        $this->show('contacts');
    }

    public function guarantee()
    {
        $this->show('guarantee');
    }

    public function delivery()
    {
        $this->show('delivery');
    }

    public function faq()
    {
        $this->show('faq');
    }

    private function generateMetaDescription($content, $length = 160)
    {
        $cleanContent = strip_tags($content);
        $cleanContent = preg_replace('/\s+/', ' ', $cleanContent);
        
        if (mb_strlen($cleanContent) > $length) {
            $cleanContent = mb_substr($cleanContent, 0, $length) . '...';
        }
        
        return $cleanContent;
    }
}