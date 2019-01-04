<?php
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

if (! function_exists('mix_cdn')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function mix_cdn($path, $manifestDirectory = '')
    {
        static $manifests = [];
        if (! Str::startsWith($path, '/')) {
            $path = "/{$path}";
        }
        if ($manifestDirectory && ! Str::startsWith($manifestDirectory, '/')) {
            $manifestDirectory = "/{$manifestDirectory}";
        }
        if (file_exists(public_path($manifestDirectory.'/hot'))) {
            $url = rtrim(file_get_contents(public_path($manifestDirectory.'/hot')));
            if (Str::startsWith($url, ['http://', 'https://'])) {
                return new HtmlString(Str::after($url, ':').$path);
            }
            return new HtmlString("//localhost:8080{$path}");
        }
        $manifestPath = public_path($manifestDirectory.'/mix-manifest.json');
        if (! isset($manifests[$manifestPath])) {
            if (! file_exists($manifestPath)) {
                throw new Exception('The Mix manifest does not exist.');
            }
            $manifests[$manifestPath] = json_decode(file_get_contents($manifestPath), true);
        }
        $manifest = $manifests[$manifestPath];
        if (! isset($manifest[$path])) {
            report(new Exception("Unable to locate Mix file: {$path}."));
            if (! app('config')->get('app.debug')) {
                return $path;
            }
        }
        $cdnStatic = env('CDN_STATIC', '');
        return new HtmlString($cdnStatic.$manifest[$path]);
    }
}

if (! function_exists('cdn')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param  string  $path
     * @param  string  $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function cdn($path = '')
    {
        $cdnStatic = env('CDN_STATIC', '');
        return new HtmlString($cdnStatic. '/' .$path);
    }
}