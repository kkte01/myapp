<footer class="container footer__master">
    <ul class="list-inline pull-right">
        <li>
            <i class="fa fa-language"></i>
        </li>
        @if(config('project.locales'))
        @foreach (config('project.locales') as $locale => $language)
            <li {!! ($locale == $currentLocale ) ? 'class="active"' : '' !!}>
                <a href="{{ route('locale', $locale) }}">
                    {{ $language }}
                </a>
            </li>
        @endforeach
        @endif
    </ul>

    <div>
        &copy; {{ date('Y') }}
        <a href="{{ config('project.url') }}">
            {{ config('app.name') }}
        </a>
    </div>
</footer>

<div>
    <a type="button" id="back-to-top" href="#" class="btn btn-sm btn-primary back-to-top" title="Top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>
