<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="heading_s1 text-center">
                <h2>Exclusive Products</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="tab-style1">
                <ul class="nav nav-tabs justify-content-center" role="tablist">
                    <li class="nav-item" id="popularTab">
                        <a class="nav-link active" data-id="popular" data-item="PopularItem" id="arrival-tab"
                            data-bs-toggle="tab" href="#Popular" role="tab" aria-controls="arrival"
                            aria-selected="true">Popular</a>
                    </li>
                    <li class="nav-item" id="newTab">
                        <a class="nav-link" data-id="new" data-item="NewItem" id="sellers-tab" data-bs-toggle="tab"
                            href="#New" role="tab" aria-controls="sellers" aria-selected="false">New</a>
                    </li>
                    <li class="nav-item" id="topTab">
                        <a class="nav-link" data-id="top" data-item="TopItem" id="featured-tab" data-bs-toggle="tab"
                            href="#Top" role="tab" aria-controls="featured" aria-selected="false">Top</a>
                    </li>
                    <li class="nav-item" id="specialTab">
                        <a class="nav-link" data-id="special" data-item="SpecialItem" id="special-tab"
                            data-bs-toggle="tab" href="#Special" role="tab" aria-controls="special"
                            aria-selected="false">Special</a>
                    </li>
                    <li class="nav-item" id="trendingTab">
                        <a class="nav-link" data-id="trending" data-item="TrendingItem" id="special-tab"
                            data-bs-toggle="tab" href="#Trending" role="tab" aria-controls="special"
                            aria-selected="false">Trending</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade show active" id="Popular" role="tabpanel" aria-labelledby="arrival-tab">
                    <div id="PopularItem" class="row shop_container">




                    </div>
                </div>
                <div class="tab-pane fade" id="New" role="tabpanel" aria-labelledby="sellers-tab">
                    <div id="NewItem" class="row shop_container">


                    </div>
                </div>
                <div class="tab-pane fade" id="Top" role="tabpanel" aria-labelledby="featured-tab">
                    <div id="TopItem" class="row shop_container">

                    </div>
                </div>
                <div class="tab-pane fade" id="Special" role="tabpanel" aria-labelledby="special-tab">
                    <div id="SpecialItem" class="row shop_container">

                    </div>
                </div>
                <div class="tab-pane fade" id="Trending" role="tabpanel" aria-labelledby="special-tab">
                    <div id="TrendingItem" class="row shop_container">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        productsByTag('popular', 'PopularItem');

        async function productsByTag(tag, tagItem) {
            let res = await axios.get(`/product-list-by-remarks/${tag}`);
            $(`#${tagItem}`).empty();

            res.data.productList?.forEach((item, i) => {
                let EachItem = `<div class="col-lg-3 col-md-4 col-6">
                                <div class="product">
                                    <div class="product_img">
                                        <a href="#">
                                            <img src="${item.image}" alt="product_img9">
                                        </a>
                                        <div class="product_action_box">
                                            <ul class="list_none pr_action_btn">
                                                <li><a href="/product-details?id=${item.id}" class="popup-ajax"><i class="icon-magnifier-add"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="product_info">
                                        <h6 class="product_title"><a  href="/product-details?id=${item.id}">${item.title}</a></h6>
                                        <div class="product_price">
                                            <span class="price">$ ${item.price}</span>
                                        </div>
                                        <div class="rating_wrap">
                                            <div class="rating">
                                                <div class="product_rate" style="width:${item.star}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`
                $(`#${tagItem}`).append(EachItem);
            })


        }

        $('.tab-style1 .nav-link').click(function() {
            productsByTag($(this).attr('data-id'), $(this).attr('data-item'));
        })
    });
</script>
