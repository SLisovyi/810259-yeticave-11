<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?=$lot['img_url']; ?>" width="350" height="260" alt="<?=$lot['name']; ?>">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?=esc($lot['cat_name']); ?></span>
        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=esc($lot['name']); ?></a></h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?=to_price($lot['last_price']); ?></span>
            </div>
            <div class="lot__timer timer <?=get_end_class($lot['date_end'])?>">
                <?=implode(':', get_time_end($lot['end_date'])); ?>
            </div>
        </div>
    </div>
</li>
