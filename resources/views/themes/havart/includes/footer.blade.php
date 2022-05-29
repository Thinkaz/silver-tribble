<div class="page-footer footer py-4 mt-5" id="footer">
    <div class="container text-center text-md-left">
        <div class="row">
            <div class="col-md-5 mt-3">
                <h6 class="text-uppercase title">{{$configs['footer_title']}}</h6>
                <p class="mb-0 desc">{{$configs['footer_description']}}</p>
            </div>

            @foreach($footerlinks as $cat => $links)
                <div class="col-md-2 mb-md-0 mb-0 mt-3">
                    <h6 class="text-white category">{{$cat}}</h6>
                    <ul class="list-unstyled">
                        @foreach($links as $link)
                            <li class="footer-link"><a href="{{$link->url}}" class="text-white-50">{{$link->name}}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            @include('includes.theme-select')
        </div>

        @if(config('cosmo.general.show_copyright') || config('cosmo.general.show_credits'))
            <hr class="mb-2 mt-2" style="background-color: var(--Accent_Color); width: auto; height: 1px; border-radius: 20px">

            <div class="copyright mb-0 pb-0">
                <div class="row justify-content-between">
                    @if(config('cosmo.general.show_copyright'))
                        <div class="col-auto">
                            <p class="mb-0 pb-0"><a href="/">@lang('cosmo.core.licensed_to', ['licensee' => $configs['site_name']])</a></p>
                        </div>
                    @endif
                    @if(config('cosmo.general.show_credits'))
                        <div class="col-auto">
                            <p class="mb-0 pb-0 text-muted">Designed by <a href="https://havasu.gg/">Havasu</a> & <a href="https://dotcore-lab.net/">dotCore</a> - @lang('cosmo.core.created_by', ['author' => 'TBDScripts']) {{ date('Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>