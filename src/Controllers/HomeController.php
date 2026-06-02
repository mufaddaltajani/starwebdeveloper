<?php

declare(strict_types=1);

namespace App\Controllers;

class HomeController extends Controller
{
    /**
     * Renders the main landing page.
     */
    public function index(): string
    {
        // We pass the AEO/SEO metadata arrays here. 
        // Notice we don't pass a 3rd parameter, so it defaults to the public 'layout'
        return $this->render('home', [
            'meta' => [
                'title' => 'AI & Web Development Solutions | StarWebDev',
                'description' => 'Lightning-fast web applications and custom AI chatbots for modern enterprises.'
            ]
        ]);
    }

    /**
     * Renders the About page.
     */
    public function about(): string
    {
        return $this->render('about', [
            'meta' => [
                'title' => 'About Us | StarWebDev',
                'description' => 'Learn about our engineering standards and global mission.'
            ]
        ]);
    }

    /**
     * Renders the Contact page.
     */
    public function contact(): string
    {
        return $this->render('contact', [
            'meta' => [
                'title' => 'Contact Us | StarWebDev',
                'description' => 'Get in touch to start your custom web or AI project today.'
            ]
        ]);
    }
}