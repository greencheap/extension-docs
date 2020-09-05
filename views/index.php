<?php $view->script('docs', 'docs:app/bundle/docs.js', 'vue') ?>
<div class="uk-grid uk-grid-collapse uk-grid-divider" uk-grid>
    <div class="uk-width-medium@m">
        <ul class="uk-nav">
            <?php foreach ($categories as $category) : ?>
                <li>
                    <h5 class="uk-heading-line uk-margin-remove"><span><?= $category->title ?></span></h5>
                    <?php if ($posts = $category->getPosts()) : ?>
                        <div class="uk-margin-bottom">
                            <ul class="uk-nav uk-nav-default">
                                <?php foreach ($posts as $sub_post) : ?>
                                    <li <?= $sub_post->id == $post->id ? 'class="uk-active"' : null ?>><a href="<?= $view->url('@docs/id', ['id' => $sub_post->id]) ?>"><?= $sub_post->title ?></a></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <div class="uk-width-expand@m uk-first-column">
        <div>
            <h1 class="uk-h1"><?= $post->title ?></h1>
            <article class="uk-comment">
                <header class="uk-comment-header">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img class="uk-comment-avatar uk-border-circle" src="<?= $view->avatar($post->user->email) ?>" width="40" height="40" alt="<?= $post->user->name ?>">
                        </div>
                        <div class="uk-width-expand">
                            <h4 class="uk-h5 uk-margin-remove"><a class="uk-link-reset" href="#"><?= $post->user->name ?></a></h4>
                            <time class="uk-text-small" datetime="'<?= $post->modified->format(\DateTime::ATOM) ?>'" v-cloak>{{ "<?= $post->modified->format(\DateTime::ATOM) ?>" | relativeDate }} Güncellendi</time>
                        </div>
                    </div>
                </header>
            </article>
            <div class="uk-margin"><?= $post->content ?></div>
        </div>
    </div>
    <div class="uk-width-medium@m uk-visible@m">
        <ul class="uk-nav uk-nav-default">
            <?php foreach ($post->links as $link) : ?>
                <li><a href="#<?= $link['src'] ?>"><?= $link['name'] ?></a></li>
            <?php endforeach ?>
        </ul>
        <?php if($post->get('sourceedit')): ?>
        <hr>
        <div class="uk-margin-top">
            <a href="<?= $post->get('sourceedit') ?>" target="_blank" rel="nofollow" class="uk-link-text"><i uk-icon="icon:github;ratio:1.3" class="uk-margin-small-right"></i><?= __('Edit') ?></a>
        </div>
        <?php endif ?>
    </div>
</div>