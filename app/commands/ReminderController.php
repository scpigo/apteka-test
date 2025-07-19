<?php
/**
 * @link https://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license https://www.yiiframework.com/license/
 */

namespace app\commands;

use Yii;
use app\models\Reminder;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\log\Logger;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ReminderController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionCheck()
    {
        $currentDate = date('Y-m-d');
        $currentTime = date('H:i');
        $reminders = Reminder::find()
            ->where(['<=', 'begin_date', $currentDate])
            ->andWhere(['>=', 'finish_date', $currentDate])
            ->andWhere(['status' => 'new'])->all();

        foreach ($reminders as $reminder) {
            if ($currentTime >= $reminder->time[0] && $currentTime <= $reminder->time[1]) {
                $this->stdout($currentDate . " " . $currentTime ." - Напоминание: " . $reminder->medicine->name . " (". $reminder->medicine->description .")" . PHP_EOL);
            }
        }

        return ExitCode::OK;
    }
}
