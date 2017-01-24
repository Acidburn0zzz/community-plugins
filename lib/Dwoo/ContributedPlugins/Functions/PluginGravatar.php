<?php
namespace Dwoo\CommunityPlugins\Functions;

use Dwoo\Compiler;
use Dwoo\ICompilable;
use Dwoo\Plugin;

/**
 * Returns a link to the gravatar of someone based on his email address, see {@link http://en.gravatar.com/}.
 * <pre>
 *  * email : email address of the user for whom you want the gravatar
 *  * size : the size in pixels of the served image, defaults to 80
 *  * default : an url to the default image to display, or one of the three image
 *              generators: identicon, monsterid or wavatar, see {@link http://en.gravatar.com/site/implement/url}
 *              for more infos on those, by default this will be the gravatar logo
 *  * rating : the highest allowed rating for the images,
 *             it defaults to 'g' (general, the lowest/safest) and other allowed
 *             values (in order) are 'pg' (parental guidance), 'r' (restricted)
 *             and 'x' (boobies, crackwhores, etc.)
 * </pre>
 */
class PluginGravatar extends Plugin implements ICompilable
{

    /**
     * @param Compiler $compiler
     * @param string   $email
     * @param null     $size
     * @param null     $default
     * @param null     $rating
     *
     * @return string
     */
    public static function compile(Compiler $compiler, $email, $size = null, $default = null, $rating = null)
    {
        $out = '\'http://www.gravatar.com/avatar/\'.md5(strtolower(trim(' . $email . ')))';

        $params = [];
        if ($size != 'null') {
            if (is_numeric($size)) {
                $params[] = 's=' . ((int)$size);
            } else {
                $params[] = 's=\'.((int) ' . $size . ').\'';
            }
        }
        if ($default != 'null') {
            $params[] = 'd=\'.urlencode(' . $default . ').\'';
        }
        if ($rating != 'null') {
            $r = trim(strtolower($rating), '"\'');
            if (in_array($r, ['g', 'pg', 'r', 'x'])) {
                $params[] = 'r=' . $r;
            } else {
                $params[] = 'r=\'.' . $rating . '.\'';
            }
        }
        if (count($params)) {
            $out .= '.\'?' . implode('&amp;', $params) . '\'';
        }

        if (substr($out, - 3) == ".''") {
            $out = substr($out, 0, - 3);
        }

        return $out;
    }
}