<?php

namespace AMZ\PostBundle\DataFixtures\ORM;

use AMZ\PostBundle\Entity\Post;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadPostData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Consultant posts
        $categories = $manager->getRepository('AMZPostBundle:Category')
            ->findAll();
        foreach ($categories as $category) {
            for ($i = 1; $i <= 15; $i++) {
                $features = array(0, 1);
                $post = new Post();
                $post->setTitle("{$category->getTitle()}-{$i}");
                $post->setSlug("{$category->getSlug()}-{$i}");
                $post->setStatus(Post::STATUS_PUBLISH);
                $post->setIsFeatured(array_rand($features, 1));
                $post->addCategory($category);
                $post->setType(Post::TYPE_POST);
                $post->setExcerpt('Năm 2015 – Năm Ất Mùi là một năm đặc biệt, đất nước ta kỷ niệm tròn 40 năm sống trong hòa bình. Sau hai cuộc chiến tranh khốc liệt và bi thảm, nhân dân ta đã tìm được ánh sáng của tự do và hạnh phúc của nền độc lập. Người dân hồi tưởng lại những năm tháng oai hùng về quá khứ, không chỉ để nhớ về công lao to lớn của lớp người đi trước mà còn khơi dậy niềm ti, động lực cho ngày mai tươi sáng.');
                $post->setThumbnail("http://nangtamvocviet.loc/web/upload/images/{$category->getSlug()}.jpg");
                $post->setContent('
                    <p>Lorem ipsum dolor sit amet, mea alienum petentium ut. Debitis minimum definitiones ei cum, eu eam viderer inermis tincidunt. Alia equidem sea eu, ad lobortis repudiandae mei, an nec atqui accumsan sadipscing. Te vis utroque indoctum, cum eu doctus labitur voluptua. Te qui eripuit ornatus, ne est qualisque consectetuer. Modo dolor tractatos in eam.</p>
                    <p>Utroque accusata in sea, ex ipsum euripidis quo, ut mei natum corpora. Ad pro soluta vidisse percipit. Vis ad laudem partem nominavi, at altera torquatos has, illum dicant labores duo ne. Consul perfecto usu ei, ne sit mutat ipsum.</p>
                    <p>Usu paulo munere bonorum at, per ad iuvaret electram adversarium. Odio sumo doctus an nam. No quo quem nihil, te reque elitr pri. Ad nec impetus eruditi deleniti, elit accumsan eos no. Nec stet malis ea, vel et omnes bonorum vocibus.</p>
                    <p>Eum prompta legimus te, id pri insolens salutatus. Ex graecis accusamus consectetuer mea. Eu mel omnis ancillae accommodare, at vis eirmod mentitum, verterem probatus consequat mel cu. Soleat graeci perpetua eu pri, apeirian recteque scribentur per an, ad patrioque inciderint pro. Mei utroque ullamcorper ei.</p>
                    <p>Est everti probatus ne, ut aliquip deterruisset mel. Mundi impetus vix in. Mea in iudicabit pertinacia, id idque debet omnium sea. Ut vel labitur dolorem gubergren.</p>
                ');
                $manager->persist($post);
            }
        }
        $page = new Post();
        $page->setTitle('Kỹ thuật cân');
        $page->setSlug('ky-thuat-can');
        $page->setStatus(Post::STATUS_PUBLISH);
        $page->setType(Post::TYPE_PAGE);
        $page->setContent('
            <p>Lorem ipsum dolor sit amet, mea alienum petentium ut. Debitis minimum definitiones ei cum, eu eam viderer inermis tincidunt. Alia equidem sea eu, ad lobortis repudiandae mei, an nec atqui accumsan sadipscing. Te vis utroque indoctum, cum eu doctus labitur voluptua. Te qui eripuit ornatus, ne est qualisque consectetuer. Modo dolor tractatos in eam.</p>
            <p>Utroque accusata in sea, ex ipsum euripidis quo, ut mei natum corpora. Ad pro soluta vidisse percipit. Vis ad laudem partem nominavi, at altera torquatos has, illum dicant labores duo ne. Consul perfecto usu ei, ne sit mutat ipsum.</p>
            <p>Usu paulo munere bonorum at, per ad iuvaret electram adversarium. Odio sumo doctus an nam. No quo quem nihil, te reque elitr pri. Ad nec impetus eruditi deleniti, elit accumsan eos no. Nec stet malis ea, vel et omnes bonorum vocibus.</p>
            <p>Eum prompta legimus te, id pri insolens salutatus. Ex graecis accusamus consectetuer mea. Eu mel omnis ancillae accommodare, at vis eirmod mentitum, verterem probatus consequat mel cu. Soleat graeci perpetua eu pri, apeirian recteque scribentur per an, ad patrioque inciderint pro. Mei utroque ullamcorper ei.</p>
            <p>Est everti probatus ne, ut aliquip deterruisset mel. Mundi impetus vix in. Mea in iudicabit pertinacia, id idque debet omnium sea. Ut vel labitur dolorem gubergren.</p>
        ');
        $manager->persist($page);
        $manager->flush();

        $page = new Post();
        $page->setTitle('Kỹ thuật đo chiều cao');
        $page->setSlug('ky-thuat-do-chieu-cao');
        $page->setStatus(Post::STATUS_PUBLISH);
        $page->setType(Post::TYPE_PAGE);
        $page->setContent('
            <p>Lorem ipsum dolor sit amet, mea alienum petentium ut. Debitis minimum definitiones ei cum, eu eam viderer inermis tincidunt. Alia equidem sea eu, ad lobortis repudiandae mei, an nec atqui accumsan sadipscing. Te vis utroque indoctum, cum eu doctus labitur voluptua. Te qui eripuit ornatus, ne est qualisque consectetuer. Modo dolor tractatos in eam.</p>
            <p>Utroque accusata in sea, ex ipsum euripidis quo, ut mei natum corpora. Ad pro soluta vidisse percipit. Vis ad laudem partem nominavi, at altera torquatos has, illum dicant labores duo ne. Consul perfecto usu ei, ne sit mutat ipsum.</p>
            <p>Usu paulo munere bonorum at, per ad iuvaret electram adversarium. Odio sumo doctus an nam. No quo quem nihil, te reque elitr pri. Ad nec impetus eruditi deleniti, elit accumsan eos no. Nec stet malis ea, vel et omnes bonorum vocibus.</p>
            <p>Eum prompta legimus te, id pri insolens salutatus. Ex graecis accusamus consectetuer mea. Eu mel omnis ancillae accommodare, at vis eirmod mentitum, verterem probatus consequat mel cu. Soleat graeci perpetua eu pri, apeirian recteque scribentur per an, ad patrioque inciderint pro. Mei utroque ullamcorper ei.</p>
            <p>Est everti probatus ne, ut aliquip deterruisset mel. Mundi impetus vix in. Mea in iudicabit pertinacia, id idque debet omnium sea. Ut vel labitur dolorem gubergren.</p>
        ');
        $manager->persist($page);
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}