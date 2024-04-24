<header id="header-area" class="header__3">
    <div class="ruby-container">
        <div class="row">
            <!-- Logo Area Start -->
            <div class="col-3 col-lg-1 col-xl-2 m-auto">
                <a href="trang-chu.html" class="logo-area">
                    <img src="assets/img/samryshell-logo.jpg" class="class_logo_atrative_logo" alt="Logo" class="img-fluid" />
                </a>
            </div>
            <!-- Logo Area End -->

            <!-- Navigation Area Start -->
            <div class="col-3 col-lg-9 col-xl-8 m-auto">
                <div class="main-menu-wrap">
                    <nav id="mainmenu">
                        <ul>
                            <li><a href="trang-chu.html">Trang chủ</a>
                            </li>
                            <li><a href="cua-hang.html">Cửa hàng</a>
                            </li>
                            <li><a href="thong-tin-cong-ty/12/gioi-thieu-cong-ty.html">Giới thiệu</a>
                            </li>
                            <li class="dropdown-show"><a href="#">Thông tin</a>
                                <ul class="dropdown-nav">
                                    <?php foreach ($ListBlog as $ListBlogmenu) : ?>
                                        <li><a href="thong-tin-cong-ty/<?php echo $ListBlogmenu['id'].'/'.$ListBlogmenu['slug'].'.html'; ?>"><?php echo $ListBlogmenu['name']; ?></a></li>
                                    <?php endforeach; ?>
                                    <!-- <li><a href="my-account.php">My Account</a></li> -->
                                </ul>
                            </li>
                            <li class="dropdown-show"><a href="#">Danh mục</a>
                                <ul class="mega-menu-wrap dropdown-nav">
                                    <li class="mega-menu-item"><a href="cua-hang.html" class="mega-item-title">Sản phẩm</a>
                                        <ul>
                                            <?php foreach ($listcategoryMenu as $listmenucategory) : ?>
                                                <li><a href="cua-hang.html?category=<?php echo $listmenucategory['categoryproduct_id']; ?>"><?php echo $listmenucategory['name']; ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>

                                    </li>
                                </ul>

                            </li>
                            <li><a href="bai-viet.html">Tin tức</a>
                            </li>
                            <li><a href="lien-he.html">Liên hệ</a></li>
                            <li class="language " style="display: inline-flex;">
                                <dd> <img src="assets/img/16x12/vn.png" alt="Eng" data-google-lang="" class="language__img"></a></dd>
                                <dd><img src="assets/img/16x12/gb.png" alt="VietNam" data-google-lang="en" class="language__img"></a></dd>
                                <dd><img src="assets/img/16x12/cn.png" alt="China" data-google-lang="zh-CN" class="language__img"></a></dd>
                                <dd><img src="assets/img/16x12/it.png" alt="Italy" data-google-lang="it" class="language__img"></a></dd>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Navigation Area End -->

            <!-- Header Right Meta Start -->
            <div class="col-6 col-lg-2 m-auto">
                <div class="header-right-meta text-right">
                    <ul>
                        <li><a href="#" class="modal-active"><i class="fa fa-search"></i></a></li>
                        <li class="settings"><a href="#"><i class="fa fa-cog"></i></a>
                            <div class="site-settings d-block d-sm-flex">
                                <dl class="currency">
                                    <dt>Tiền tệ</dt>
                                    <dd class="current"><a href="#">USD</a></dd>
                                    <dd><a href="#">AUD</a></dd>
                                    <dd><a href="#">CAD</a></dd>
                                    <dd><a href="#">BDT</a></dd>
                                </dl>

                                <dl class="my-account">
                                    <dt>Tài khoản</dt>
                                    <dd><a href="my-account/tai-khoan-cua-toi.html">Dashboard</a></dd>
                                    <dd><a href="my-account/tai-khoan-cua-toi.html">Profile</a></dd>
                                    <dd><a href="#">Sign</a></dd>
                                </dl>

                                <dl class="language">
                                    <dt>Language</dt>
                                    <dd> <img src="assets/img/16x12/vn.png" alt="Eng" data-google-lang="" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/gb.png" alt="VietNam" data-google-lang="en" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/cn.png" alt="China" data-google-lang="zh-CN" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/fr.png" alt="France" data-google-lang="fr" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/it.png" alt="Italy" data-google-lang="de" class="language__img"></a></dd>
                                </dl>
                                <dl class="language">
                                    <dt>Language</dt>
                                    <dd> <img src="assets/img/16x12/vn.png" alt="Eng" data-google-lang="" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/in.png" alt="VietNam" data-google-lang="hi" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/sa.png" alt="China" data-google-lang="ar" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/pt.png" alt="France" data-google-lang="pt" class="language__img"></a></dd>
                                    <dd><img src="assets/img/16x12/jp.png" alt="German" data-google-lang="ja" class="language__img"></a></dd>
                                </dl>
                            </div>
                        </li>
                        <li class="shop-cart"><a href="#"><i class="fa fa-shopping-bag"></i> <span class="count"><?php echo isset($countcart) ? $countcart : 0; ?></span></a>
                            <div class="mini-cart">
                                <div class="mini-cart-body">
                                    <?php if (isset($listcart) && is_array($listcart) && !empty($listcart)) : ?>
                                        <?php foreach ($listcart as $product) : ?>
                                            <div class="single-cart-item d-flex">
                                                <figure class="product-thumb">
                                                    <a href="#"><img class="img-fluid" src="<?php
                                                                                            $strfirt = './admin-page';
                                                                                            echo  $strfirt . substr($product['image'], 2); ?>" alt="Products" /></a>
                                                </figure>

                                                <div class="product-details">
                                                    <h2><a href="#"><?php echo $product['name']; ?></a></h2>
                                                    <div class="cal d-flex align-items-center">
                                                        <span class="quantity"><?php echo $product['quantity']; ?></span>
                                                        <span class="multiplication">X</span>
                                                        <span class="price">$<?php echo $product['price']; ?></span>
                                                    </div>
                                                </div>
                                                <form method="post">
                                                    <input type="hidden" value="<?php echo $product['product_id']; ?>" name="product_id_delete">
                                                    <button name="delete_cart" href="#" class="remove-icon" type="submit"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <p>Không có sản phẩm trong giỏ hàng.</p>
                                    <?php endif; ?>

                                    <input type="hidden" name="email_login_insert_cart" id="email_login_insert_cart" value="<?php echo $emaillist; ?>">
                                </div>
                                <div class="mini-cart-footer">
                                    <a onclick="AddSaleCart(event)" class="btn-add-to-cart">Checkout</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- Header Right Meta End -->
        </div>
    </div>
</header>