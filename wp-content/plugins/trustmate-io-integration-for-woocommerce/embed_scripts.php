<?php

function trustmate_render_widget_alpaca()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_alpaca')) {
        echo "<div id='tm-widget-alpaca'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/alpaca/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-alpaca', $script_src);
    }
}

function trustmate_render_widget_badger2()
{
    global $product;

    $language_param = class_exists('SitePress') ? '&language='.ICL_LANGUAGE_CODE : '';

    if (is_product() && get_option('trustmate_widget_badger2')) {
        echo "<div id='tm-widget-badger2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/badger2/script/%s?%s=%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            $product->get_type() === 'variable' ? 'group' : 'product',
            $product->get_id()
        );
        wp_enqueue_script('trustmate-badger2', $script_src);
    }
}

function trustmate_render_widget_muskrat2()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_muskrat2')) {
        echo "<div id='tm-widget-muskrat2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/muskrat2/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-muskrat2', $script_src);
    }
}

function trustmate_render_widget_bee()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_bee')) {
        echo "<div id='tm-widget-bee'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/bee/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-bee', $script_src);
    }
}

function trustmate_render_widget_lemur()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_lemur')) {
        echo "<div id='tm-widget-lemur'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/lemur/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-lemur', $script_src);
    }
}

// Uses option for v1 but still renders v2
function trustmate_render_widget_chupacabra()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_chupacabra')) {
        echo "<div id='tm-widget-chupacabra2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/chupacabra2/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-chupacabra', $script_src);
    }
}

function trustmate_render_widget_ferret2()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_ferret2')) {
        echo "<div id='tm-widget-ferret2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/ferret2/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-ferret2', $script_src);
    }
}

function trustmate_render_widget_product_ferret2()
{
    global $product;

    $language_param = class_exists('SitePress') ? '&language='.ICL_LANGUAGE_CODE : '';

    if (is_product() && get_option('trustmate_widget_product_ferret2')) {
        echo "<div id='tm-widget-productFerret2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/productFerret2/script/%s?%s=%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            $product->get_type() === 'variable' ? 'group' : 'product',
            $product->get_id()
        );
        wp_enqueue_script('trustmate-product-ferret2', $script_src);
    }
}

function trustmate_render_widget_hydra()
{
    global $product;

    $language_param = class_exists('SitePress') ? '&language='.ICL_LANGUAGE_CODE : '';

    if (is_product() && get_option('trustmate_widget_hydra')) {
        echo "<div id='tm-widget-hydra'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/hydra/script/%s?%s=%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            $product->get_type() === 'variable' ? 'group' : 'product',
            $product->get_id()
        );
        wp_enqueue_script('trustmate-hydra', $script_src);
    }
}

// Uses option for v1 but still renders v2
function trustmate_render_widget_owl()
{
    $language_param = class_exists('SitePress') ? '?language='.ICL_LANGUAGE_CODE : '';

    if (get_option('trustmate_widget_owl')) {
        echo "<div id='tm-widget-owl2'></div>";
        $script_src = sprintf(
            "%s/platforms/widget/owl2/script/%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
        wp_enqueue_script('trustmate-owl', $script_src);
    }
}

function trustmate_insert_hornet_wrappers() {
    global $product;

    if (get_option('trustmate_widget_hornet')) {
        echo sprintf(
            "<div class='tm-widget-hornet-wrapper' data-product_id='%s'></div>",
            $product->get_id()
        );
    }
}

function trustmate_render_widget_hornets()
{
    $language_param = class_exists('SitePress') ? '&language='.ICL_LANGUAGE_CODE : '';

    if ((is_shop() || is_product_category()) && get_option('trustmate_widget_hornet')) {
        echo sprintf(
            "<div id='tm-widget-hornet'></div>
            <script>
                (() => {
                    async function insertHornets() {
                        const lastGoodProductStorageKey = 'tm-last-good-product';
                        const idArray = [];
                        const hornetWrappers = document.querySelectorAll('.tm-widget-hornet-wrapper');
                        const lastGoodProduct = getLastGoodProductFromStorage();
                        if (lastGoodProduct) {
                            idRawArray.push(lastGoodProduct);
                        }
                        hornetWrappers.forEach(element => {
                            idArray.push(element.getAttribute('data-product_id'));
                        });
                        idArray.sort((a, b) => a - b);

                        function getLastGoodProductFromStorage() {
                            return parseInt(window.localStorage.getItem(lastGoodProductStorageKey), 10);
                        }

                        function setLastGoodProductToStorage(productId) {
                            window.localStorage.setItem(lastGoodProductStorageKey, productId);
                        }

                        async function getWidgetHtml(localId) {
                            try {
                                const response = await fetch(`%s/platforms/widget/hornet/script/%s?product=\${localId}{$language_param}`);
                                if (response.status !== 200) return null;

                                const widget = await response.text();
                                const splitWidget = widget.split(`fetch('`)[1];
                                return splitWidget.split(`'`)[0]+'&disable-extra=1';
                            } catch (error) {
                                return null;
                            }
                        }

                        async function getProductsData() {
                            const url = new URL('%s/platforms/product/ratings/%s');
                            Object.keys(idArray).forEach((key) => url.searchParams.append('p[]', idArray[key]));
                            try {
                                const response = await fetch(url.href);
                                if (response.status !== 200) return null;
                                return await response.json();
                            } catch (error) {
                                return null;
                            }
                        }

                        async function loadWidgetHtml(linkToWidget, productsData) {
                            try {
                                const response = await fetch(linkToWidget);
                                if (response.status !== 200) return null;
                                return await response.text();
                            } catch (error) {
                                return null;
                            }
                        }

                        function getStarSrc(oldSrc, averageGrade) {
                            const src = oldSrc.substr(0, oldSrc.lastIndexOf('/'));

                            if (averageGrade >= 4.75) {
                                return src + '/5.png';
                            }
                            else if (averageGrade >= 4.25) {
                                return src + '/4-5.png';
                            }
                            else if (averageGrade >= 3.75) {
                                return src + '/4.png';
                            }
                            else if (averageGrade >= 3.25) {
                                return src + '/3-5.png';
                            }
                            else if (averageGrade >= 2.75) {
                                return src + '/3.png';
                            }
                            else if (averageGrade >= 2.25) {
                                return src + '/2-5.png';
                            }
                            else if (averageGrade >= 1.75) {
                                return src + '/2.png';
                            }
                            else if (averageGrade >= 1.25) {
                                return src + '/1-5.png';
                            }
                            else if (averageGrade >= 1) {
                                return src + '/1.png';
                            }
                            else {
                                return src + '/0.png';
                            }
                        }

                        const productsData = await getProductsData();
                        if (productsData === null) return null;
                        const firstItemWithReviews = productsData.items.find(({ reviewsCount }) => reviewsCount > 0);
                        if (!firstItemWithReviews) return null;
                        const linkToWidget = await getWidgetHtml(firstItemWithReviews?.localId);
                        if (linkToWidget === null) return null;
                        const html = await loadWidgetHtml(linkToWidget, productsData);
                        if (html === null) return null;

                        function findConfigValue(element, elementToFind) {
                          let valueIndex = element.search(elementToFind);
                          if (valueIndex === -1) return null;
                          return element.slice(valueIndex + elementToFind.length +1, valueIndex + elementToFind.length + 2) === '1' ? true : false;
                        }

                        let firstProduct = true;

                        hornetWrappers.forEach((element, index) => {
                            const productData = productsData.items.find(({ localId }) => localId === hornetWrappers[index].getAttribute('data-product_id'));
                            const widgetDiv = document.createElement('div');
                            widgetDiv.innerHTML = html;

                            const showWithoutReviews = findConfigValue(html, 'tmShowWithoutReviews=');
                            const show0ReviewsNumber = findConfigValue(html, 'tmShowZeroReviewsNumber=');
                            const showOnMobile = findConfigValue(html, 'tmShowOnMobile=');

                            if (showWithoutReviews === null || show0ReviewsNumber === null || showOnMobile === null) return;

                            let averageGrade = productData?.averageGrade ?? 0;
                            let reviewsCount = productData?.reviewsCount ?? 0;

                            if (!showWithoutReviews && 0 === reviewsCount) {
                                widgetDiv.style.visibility = 'hidden';
                            }

                            let averageGradeText = reviewsCount ? averageGrade.toFixed(1) : '';

                            if ('script' === widgetDiv.children[0].tagName.toLowerCase()) {
                                widgetDiv.children[0].remove();
                            }
                            if (!firstProduct && 'link' === widgetDiv.children[0].tagName.toLowerCase()) {
                                widgetDiv.children[0].remove();
                            }
                            firstProduct = false;

                            element.append(widgetDiv);

                            widgetDiv.style.paddingTop = '8px';
                            widgetDiv.style.paddingBottom = '8px';

                            if (!showOnMobile) {
                              widgetDiv.classList.add('hide-on-mobile');
                            }

                            const { title } = widgetDiv.getElementsByClassName('tm-hornet-wrapper')[0];

                            const lastIndex = title.lastIndexOf(' ');
                            text = title.substr(0, lastIndex) + ' ' + reviewsCount;

                            if (lastIndex > 0) {
                                widgetDiv.getElementsByClassName('tm-hornet-wrapper')[0].title = text;
                            }
                            if (!showWithoutReviews && 0 === reviewsCount || reviewsCount !== 0) {
                                averageGradeText = averageGradeText + ' <span>(' + reviewsCount + ')</span>';
                            }
                            widgetDiv.getElementsByClassName('tm-grade-label__text')[0].innerHTML = averageGradeText;

                            const stars = widgetDiv.getElementsByClassName('tm-grade-label__stars')[0].children[0];
                            stars.src = getStarSrc(stars.src, averageGrade);
                        });
                    }
                    window.setTimeout(insertHornets, 10, true);
                })();
            </script>",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid()
        );
    }
}

function trustmate_render_widget_hornet()
{
    global $product;

    $language_param = class_exists('SitePress') ? '&language='.ICL_LANGUAGE_CODE : '';

    if (is_product() && get_option('trustmate_widget_hornet')) {
        echo "<div id='tm-widget-hornet'></div>";
        $script_src = sprintf("%s/platforms/%s/widget/hornet/script?%s=%s{$language_param}",
            trustmate_get_api_base_url(),
            trustmate_get_current_uuid(),
            $product->get_type() === 'variable' ? 'group' : 'product',
            $product->get_id()
        );
        echo "<script>
                (() => {
                    function styleHornet() {
                        const hornetRef = document.getElementById('tm-widget-hornet');
                        hornetRef.style.marginBottom = '16px';
                    }
                    window.setTimeout(styleHornet, 10, true);
                })();
            </script>";
        wp_enqueue_script('trustmate-hornet', $script_src);
    }

    if (get_option('trustmate_widget_gorilla') || get_option('trustmate_widget_product_ferret') || get_option('trustmate_widget_product_ferret2') || get_option('trustmate_widget_hydra')) {
        echo sprintf(
        "<script>
            (() => {
                function scrollToWidget() {
                    const hornetRef = document.getElementById('tm-widget-hornet');
                    let widgetRef = document.getElementById('tm-widget-productFerret');
                    if (!widgetRef) {
                        widgetRef = document.getElementById('tm-widget-gorilla');
                    }
                    if (!widgetRef) {
                        widgetRef = document.getElementById('tm-hydra');
                    }
                    if (!widgetRef) {
                        widgetRef = document.getElementById('tm-ferret2');
                    }
                    if (widgetRef) {
                        hornetRef.addEventListener('click', () => {
                            hornetRef.click();
                            setTimeout(() => {
                                let widgetPosition = widgetRef?.getBoundingClientRect();
                                if (widgetPosition) {
                                    window.scrollTo({top: widgetPosition.top + window.scrollY - 250, behavior: 'smooth'});
                                }
                            }, 100);
                        });
                    }
                }
                window.setTimeout(scrollToWidget, 10, true);
            })();
        </script>"
    );
    }
}