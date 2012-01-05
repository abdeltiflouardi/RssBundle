## site using this bundle http://www.phphub.net/flux.rss

Installation using github
=========================

in your file deps add this lines

    [OSRssBundle]
        git=http://github.com/ouardisoft/RssBundle.git
        target=bundles/OS/RssBundle

Execute

    php bin/vendors install

Add in your file app/AppKernel.php

    ...
    public function registerBundles() {
       $bundles = array(
            ...
            new OS\RssBundle\OSRssBundle(),
            ...
    }    

Add in your file app/autoload.php

    $loader->registerNamespaces(array(
        ...
        'OS' => __DIR__ . '/../vendor/bundles',
        ...
     

Configuration
=============

example
-------

    os_rss:
      title: PHPhub - php coding,
      description: The lastest articles
      language: en
      webMaster: contact@phphub.net
      link: www.phphub.net
      item:
        entity: AppCoreBundle:Post
        title: title
        description: body
        pubDate: updatedAt
        guid: {route: _post, params: {post_id: id, title: slug}}}
        link: {route: _post, params: {post_id: id, title: slug}}}     

My route is:
_post:
  pattern: /{post_id}/{title}/

My database table
  post(id, title, slug, text, createdAt, updatedAt)

if you have not slug field and you want to generate slug from title field use this configuration

link: {route: _post, params: {post_id: id, {field: title, class: App\CodeBundle\Inflector, method: slug}}}

add in your app/config/routing.yml
----------------------------------

    OSRssBundle:
      resource: "@OSRssBundle/Controller/"
      type: annotation
      prefix: /

Browse
http://yourserver/flux.rss

