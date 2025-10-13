<?php

namespace Wpcb2\Service;


class ThemeMapper
{

    private $themeMapping = [
        'ace/theme/clouds' => 'clouds',
        'ace/theme/clouds_midnight' => 'clouds-midnight',
        'ace/theme/cobalt' => 'cobalt',
        'ace/theme/dawn' => 'dawn',
        'ace/theme/dracula' => 'dracula',
        'ace/theme/dreamweaver' => 'dreamweaver',
        'ace/theme/github' => 'github',
        'ace/theme/idle_fingers' => 'idle-fingers',
        'ace/theme/iplastic' => 'iplastic',
        'ace/theme/katzenmilch' => 'katzenmilch',
        'ace/theme/kuroir' => 'kuroir',
        'ace/theme/merbivore' => 'merbivore',
        'ace/theme/merbivore_soft' => 'merbivore-soft',
        'ace/theme/mono_industrial' => 'mono-industrial',
        'ace/theme/monokai' => 'monokai',
        'ace/theme/pastel_on_dark' => 'pastel-on-dark',
        'ace/theme/solarized_dark' => 'solarized-dark',
        'ace/theme/solarized_light' => 'solarized-light',
        'ace/theme/textmate' => 'textmate',
        'ace/theme/tomorrow' => 'tomorrow',
        'ace/theme/tomorrow_night' => 'tomorrow-night',
        'ace/theme/tomorrow_night_blue' => 'tomorrow-night-blue',
        'ace/theme/tomorrow_night_bright' => 'tomorrow-night-bright',
        'ace/theme/tomorrow_night_eighties' => 'tomorrow-night-eighties',
        'ace/theme/twilight' => 'twilight',
        'ace/theme/vibrant_ink' => 'vibrant-ink',
        'ace/theme/xcode' => 'xcode',
        'ace/theme/ambiance' => 'vs-dark',
        'ace/theme/chrome' => 'vs-light',
        'ace/theme/crimson_editor' => 'vs-light',
        'ace/theme/eclipse' => 'vs-light',
        'ace/theme/gob' => 'vs-dark',
        'ace/theme/gruvbox' => 'vs-dark',
        'ace/theme/sql_server' => 'vs-light',
        'ace/theme/terminal' => 'vs-dark',
    ];

    public function getTheme($theme)
    {
        if(strpos($theme, 'ace/theme') !== false) {
            return $this->themeMapping[$theme];
        } else {
            return $theme;
        }
    }
}
