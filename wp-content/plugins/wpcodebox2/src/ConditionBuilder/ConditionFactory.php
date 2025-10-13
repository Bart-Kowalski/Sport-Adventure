<?php

namespace Wpcb2\ConditionBuilder;


use Wpcb2\ConditionBuilder\Condition\CustomPhp;
use Wpcb2\ConditionBuilder\Condition\DayOfWeek;
use Wpcb2\ConditionBuilder\Condition\IsMobile;
use Wpcb2\ConditionBuilder\Condition\Location;
use Wpcb2\ConditionBuilder\Condition\LoggedInUser;
use Wpcb2\ConditionBuilder\Condition\LoggedInUserRole;
use Wpcb2\ConditionBuilder\Condition\PageUrl;
use Wpcb2\ConditionBuilder\Condition\Post;
use Wpcb2\ConditionBuilder\Condition\PostParent;
use Wpcb2\ConditionBuilder\Condition\PostType;
use Wpcb2\ConditionBuilder\Condition\Taxonomy;
use Wpcb2\ConditionBuilder\Condition\Time;
use Wpcb2\ConditionBuilder\Condition\UserLoggedIn;

class ConditionFactory
{
    /**
     * @var WordPressContext
     */
    private $wordPressContext;

    public function __construct(WordPressContext $wordPressContext)
    {

        $this->wordPressContext = $wordPressContext;
    }

    public function create_condition($condition_type, ConditionData $condition_data) {

        switch ($condition_type) {
            case 'Location':
                return new Location($this->wordPressContext, $condition_data);
                break;
            case 'Current Post' :
                return new Post($this->wordPressContext, $condition_data);
                break;
            case 'Current Post Type' :
                return new PostType($this->wordPressContext, $condition_data);
                break;
            case 'Current Post Parent' :
                return new PostParent($this->wordPressContext, $condition_data);
                break;
            case 'Taxonomy' :
                return new Taxonomy($this->wordPressContext, $condition_data);
                break;
            case 'Custom PHP Condition':
                return new CustomPhp($this->wordPressContext, $condition_data);
                break;
            case 'Page URL':
                return new PageUrl($this->wordPressContext, $condition_data);
                break;
            case 'Current Logged In User':
                return new LoggedInUser($this->wordPressContext, $condition_data);
                break;
            case 'Current Logged In User Role':
                return new LoggedInUserRole($this->wordPressContext, $condition_data);
                break;
            case 'Time':
                return new Time($this->wordPressContext, $condition_data);
                break;
            case 'Day Of The Week':
                return new DayOfWeek($this->wordPressContext, $condition_data);
                break;
            case 'User Logged-in Status':
                return new UserLoggedIn($this->wordPressContext, $condition_data);
                break;
            case 'User Device':
                return new IsMobile($this->wordPressContext, $condition_data);
                break;
            default:
                throw new \Exception('Unknown condition type');

        }

    }
}
