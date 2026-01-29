
<footer>
    <div class="footer-top-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-4">
                    <div class="footer-widget">
                        <!-- Start Logo -->
                        <div class="logo footer-logo text-uppercase">
                            <h1>
                                @if(inicompute($homepage))
                                    <?php $hometype = (isset($homepage->pageID) ? 'page' : (isset($homepage->postID) ? 'post' : '')); ?>
                                    <a href="{{ site_url('frontend/'.$hometype.'/'.$homepage->url) }}"> {{ frontenddata::frontendColorStyle(namesorting($backend->system_name, 16)) }} </a>
                                @else
                                    <a> {{ frontenddata::frontendColorStyle(namesorting($backend->system_name, 16)) }} </a>
                                @endif

                            </h1>
                        </div>
                        <!-- End Logo -->
                        <p>{{ frontenddata::get_frontend('description') }}</p>
                        <div class="footer-social">
                            <ul>
                                <li><a href="{{ frontenddata::get_frontend('facebook') }}"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="{{ frontenddata::get_frontend('twitter') }}"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="{{ frontenddata::get_frontend('linkedin') }}"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="{{ frontenddata::get_frontend('youtube') }}"><i class="fa fa-youtube"></i></a></li>
                                <li><a href="{{ frontenddata::get_frontend('google') }}"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if(isset($menu['frontendSocialQueryMenus']))
                    @if(inicompute($menu['frontendSocialQueryMenus']))
                        <div class="col-md-3 col-md-offset-1 col-sm-4">
                            <div class="footer-widget">
                                <h2>Information</h2>
                                <ul>
                        <?php $i = 1; ?>
                        <?php $countFrontendSocialQueryMenus = inicompute($menu['frontendSocialQueryMenus']); ?>
                        @foreach ($menu['frontendSocialQueryMenus'] as $frontendSocialQueryMenu)
                            <?php 
                                $url = '#';
                                if($frontendSocialQueryMenu->menu_typeID == 1) {
                                    if(isset($fpages[$frontendSocialQueryMenu->menu_pageID])) {
                                        $url = site_url('frontend/page/'.$fpages[$frontendSocialQueryMenu->menu_pageID]->url);
                                    }
                                } elseif ($frontendSocialQueryMenu->menu_typeID == 2) {
                                    if(isset($fposts[$frontendSocialQueryMenu->menu_pageID])) {
                                        $url = site_url('frontend/post/'.$fposts[$frontendSocialQueryMenu->menu_pageID]->url);
                                    }
                                } elseif($frontendSocialQueryMenu->menu_typeID == 3) {
                                    $url = $frontendSocialQueryMenu->menu_link;
                                }
                            ?>
                            <li><a href="{{ $url }}">{{ $frontendSocialQueryMenu->menu_label }}</a></li>
                            @if($i == 5)
                                        </ul>
                                    </div>
                                </div>
                                @if($countFrontendSocialQueryMenus > 5)
                                    <div class="col-md-3 col-md-offset-1 col-sm-4">
                                        <div class="footer-widget">
                                            <h2>Usefull links</h2>
                                            <ul>
                                @endif
                            @endif
                            @if(($i == 10) || ($countFrontendSocialQueryMenus == $i))

                                        </ul>
                                    </div>
                                </div>
                                <?php break; ?>
                            @endif
                            <?php $i++; ?>
                        @endforeach
                    @endif
                @endif
                
            </div>
        </div>
    </div>
    <div class="footer-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="copyright text-center">
                        {{ frontendData::get_backend('footer_text') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>