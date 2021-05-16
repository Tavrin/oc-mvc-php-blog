<?php


namespace App\Twig;


use Core\utils\StringUtils;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('moment', [$this, 'getMoment']),
            new TwigFilter('parseEditor', [$this, 'parseEditor']),
        ];
    }

    public function getMoment($datetime): string
    {
        $time = time() - strtotime($datetime);

        $units = array (
            31536000 => 'année',
            2592000 => 'mois',
            604800 => 'semaine',
            86400 => 'jour',
            3600 => 'heure',
            60 => 'minute',
            1 => 'seconde'
        );

        foreach ($units as $unit => $val) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return ($val == 'seconde')? 'il y a quelques secondes' : 'il y a  '.$numberOfUnits. ' '.$val.(($numberOfUnits>1 && $val !== 'mois') ? 's' : '').' ';
        }
    }

    public function parseEditor($content, $listing = false): string
    {
        $parsedContent = '';
        $content = json_decode($content, true);
        foreach ($content['blocks'] as $block) {
            switch ($block['type']) {
                case 'header':
                    if (true === $listing) {
                        break;
                    }
                    $headerLevel = $block['data']['level'];
                    1 === $headerLevel ? $class = 'ta-c' : $class = 'ta-l';
                    $id = StringUtils::slugify($block['data']['text']);
                    $parsedContent .= "<h{$headerLevel} id={$id} class='fw-700 mb-0-5 mt-1 {$class}'>{$block['data']['text']}</h{$headerLevel}>";
                    break;
                case 'paragraph':
                    $parsedContent .= $this->setParagraph($block['data']);
                    break;
                case 'list':
                    if (true === $listing) {
                        break;
                    }
                    $parsedContent .= $this->setList($block['data']);
                    break;
                case 'delimiter':
                    if (true === $listing) {
                        break;
                    }
                    $parsedContent .= '<hr>';
                    break;
                case 'code':
                    if (true === $listing) {
                        break;
                    }
                    $parsedContent .= '<pre><code class="language-php ff-i">' . PHP_EOL . $block['data']['code'] . PHP_EOL . '</code></pre>';
                    break;
                case 'mediaPicker':
                case 'image':
                if (true === $listing) {
                    break;
                }
                    $parsedContent .= "<div class='post-show-content-media'><img src='{$block['data']['url']}' alt='image'><figcaption class='text-muted pt-0-5'>{$block['data']['caption']}</figcaption></div>";
                    break;
                default:
                    break;
            }

            $parsedContent .= PHP_EOL;
        }
        return $parsedContent;
    }

    private function setList(array $list): ?string
    {
        if (empty($list['items'])) {
            return null;
        }
        $parsedList = '';
        switch ($list['style']) {
            case 'unordered':
                $parsedList .= '<ul>' . PHP_EOL;
                foreach ($list['items'] as $item) {
                    $parsedList .= "<li class='li-dot'>{$item}</li>" . PHP_EOL;
                }
                $parsedList .= '</ul>';
                break;
            case 'ordered':
                $parsedList .= '<ol>' . PHP_EOL;
                foreach ($list['items'] as $item) {
                    $parsedList .= "<li class='li-num'>{$item}</li>" . PHP_EOL;
                }
                $parsedList .= '</ol>';
                break;
        }

        return $parsedList;
    }

    private function setParagraph(array $paragraph): string
    {
        $generalCss = 'mb-1 lh-1c7';
        switch ($paragraph['alignment']) {
            case 'left':
                $text = "<p class='ta-l {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'center':
                $text = "<p class='ta-c {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'right':
                $text = "<p class='ta-r {$generalCss}'>{$paragraph['text']}</p>";
                break;
            case 'justify':
                $text = "<p class='ta-j {$generalCss}'>{$paragraph['text']}</p>";
                break;
            default:
                $text = "<p class='{$generalCss}'>{$paragraph['text']}</p>";
        }

        return $text;
    }
}