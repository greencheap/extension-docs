<?php $view->script('docs', 'docs:app/bundle/docs.js', 'vue') ?>
<div id="app" class="uk-grid uk-grid-collapse uk-grid-divider" uk-grid>
    <div class="uk-width-medium@m">
        <ul class="uk-nav">
            <?php foreach ($categories as $category) : ?>
                <li>
                    <h5 class="uk-heading-line uk-margin-remove"><span><?= $category->title ?></span></h5>
                    <?php if ($posts = $category->getPosts()) : ?>
                        <div class="uk-margin-bottom">
                            <ul class="uk-nav uk-nav-default">
                                <?php foreach ($posts as $sub_post) : ?>
                                    <li <?= $sub_post->slug == $doc->slug ? 'class="uk-active"' : null ?>><a href="<?= $view->url('@docs/slug', ['slug' => $sub_post->slug]) ?>"><?= $sub_post->title ?></a></li>
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
            <h1 class="uk-h1"><?= $doc->title ?></h1>
            <article class="uk-comment">
                <header class="uk-comment-header">
                    <div class="uk-grid-small uk-flex-middle" uk-grid>
                        <div class="uk-width-auto">
                            <img class="uk-comment-avatar uk-border-circle" src="<?= $view->avatar($doc->user->email) ?>" width="40" height="40" alt="<?= $doc->user->name ?>">
                        </div>
                        <div class="uk-width-expand">
                            <h4 class="uk-h5 uk-margin-remove"><a class="uk-link-reset" href="#"><?= $doc->user->name ?></a></h4>
                            <time class="uk-text-small" datetime="'<?= $doc->modified->format(\DateTime::ATOM) ?>'" v-cloak>{{ "<?= $doc->modified->format(\DateTime::ATOM) ?>" | relativeDate }} Güncellendi</time>
                        </div>
                    </div>
                </header>
            </article>
            <div>
                <?= $doc->content ?>
            </div>
        </div>
    </div>
    <div class="uk-width-medium@m uk-visible@m">
        <ul class="uk-nav uk-nav-default">
            <?php foreach ($doc->links as $link) : ?>
                <li><a href="#<?= $link['src'] ?>"><?= $link['name'] ?></a></li>
            <?php endforeach ?>
        </ul>
    </div>
</div>