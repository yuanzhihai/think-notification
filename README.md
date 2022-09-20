# ThinkPHP6 消息通知扩展

支持`mail` `sms` `database`等驱动

## 应用场景

> 发送手机验证码  
> 发送验证邮件，找回密码邮件 
> 订单状态变更  
> 站内消息通知  
> ...  

## 安装
~~~
composer require yzh52521/think-notification
~~~

## 创建通知
通常每个通知都由一个存储在 app/notifications 目录下的一个类表示。如果在你的应用中没有看到这个目录，不要担心，当运行 make:notification 命令时它将为您创建：
```php
php think make:notification InvoicePaid
```
这个命令会在 app/notifications 目录下生成一个新的通知类。每个通知类都包含一个 channels 方法以及一个或多个消息构建的方法比如 toMail 或 toDatabase，它们会针对特定的渠道把通知转换为对应的消息。

## 发送通知
使用 Notifiable Trait

通知可以通过两种方式发送： 使用 Notifiable 特性的 notify 方法或使用 Notification 门面

```php
<?php

namespace app\model;

use yzh52521\notification\Notifiable;

class User 
{
    use Notifiable;
}
```
此 notify 方法需要接收一个通知实例参数：
```php
use app\notification\InvoicePaid;

$user->notify(new InvoicePaid($invoice));
```
> 请记住，你可以在任何模型中使用 Notifiable trait。而不仅仅是在 User 模型中。

## 使用 Notification Facade
另外，你可以通过 Notification facade 来发送通知，它主要用在当你需要给多个可接收通知的实体发送的时候，比如给用户集合发送通知。使用 Facade 发送通知的话，要把可接收通知实例和通知实例传递给 send 方法：
```php

use yzh52521\facade\Notification;

Notification::send($users, new InvoicePaid($invoice));
```
您也可以使用 sendNow 方法立即发送通知。即使通知实现了 ShouldQueue 接口，该方法也会立即发送通知：

```php
Notification::sendNow($developers, new DeploymentCompleted($deployment));

```
## 发送指定频道
每个通知类都有一个 channels 方法，用于确定将在哪些通道上传递通知。通知可以在 mail、database、sms 频道上发送。

channels 方法接收一个 $notifiable 实例，这个实例将是通知实际发送到的类的实例。你可以用 $notifiable 来决定这个通知用哪些频道来发送：

```php
/**
 * 获取通知发送频道
 *
 * @param  mixed  $notifiable
 * @return array
 */
public function channels($notifiable)
{
    return $notifiable->prefers_sms ? ['sendcloud'] : ['mail', 'database'];
}
```
### 通知队列化
> 注意：使用通知队列前需要配置队列并 开启一个队列任务。

发送通知可能是耗时的，尤其是通道需要调用额外的 API 来传输通知。为了加速应用的响应时间，可以将通知推送到队列中异步发送，而要实现推送通知到队列，可以让对应通知类实现 ShouldQueue 。如果通知类是通过 make:notification 命令生成的，你可以快速将它们添加到通知类：
```php
<?php

namespace app\notifications;

use think\queue\ShouldQueue;
use yzh52521\Notification;
use yzh52521\notification\message\Mail;
use yzh52521\notification\Notifiable;

class InvoicePaid extends Notification implements ShouldQueue
{

    // ...
}
```
一旦 ShouldQueue 接口被添加到您的通知中，您就可以像往常一样发送通知。 Thinkphp 将检测类上的 ShouldQueue 接口并自动将通知的传递排队：

```php
$user->notify(new InvoicePaid($invoice));
```
如果您想延迟通知的传递，您可以设置 delay：

```php
 public function __construct(Order $order)
    {
         $this->order = $order;
         $this->delay =5;
    }
```
自定义通知队列连接：
默认情况下，排队通知将使用应用程序的默认队列连接进行排队。如果您想为特定通知指定一个不同的连接，您可以在通知类上定义一个 $connection 属性：

```php
 public function __construct(Order $order)
    {
         $this->order = $order;
         public $connection = 'redis';
         $this->delay =5;
    }
```

## 邮件通知
格式化邮件信息

如果通知支持作为电子邮件发送，您应该在通知类上定义一个 toMail 方法。 此方法将接收一个 $notifiable 实体并应返回一个 yzh52521\notification\message\Mail 实例。

Mail 类包含一些简单的方法来帮助您构建事务性电子邮件消息。 邮件消息可能包含文本行以及「动作的调用」。让我们看一个示例 toMail 方法：

```php
/**
 * 获取通知的邮件描述。
 *
 * @param  mixed  $notifiable
 * @return \yzh52521\notification\messages\Mail
 */
public function toMail($notifiable)
{
    $url = url('/invoice/'.$this->invoice->id);
    return (new Mail)
                ->greeting('Hello!')
                ->line('你的一张发票已经付款了！')
                ->action('查看发票', $url)
                ->line('感谢您使用我们的应用程序！');
}

```

自定义模板 
使用 view()  不需要使用 greeting 、 line 、action 等参数 在 view 第二个参数传递
```php
public function toMail($notifiable)
{
    return (new Mail)
        ->to($to)
        ->subject('找回密码')
        ->view('emails.name', ['invoice' => $this->invoice]);
}

```
自定义发件人
默认情况下, 发件人地址在 config/mail.php 配置文件中定义。但是，您可以使用 from 方法指定特定通知的发件人地址：
```php
public function toMail($notifiable)
{
    return (new Mail)
                ->from('barrett@example.com', 'Barrett Blair')
                ->line('...');
}
```

其他用法 参考 yzh52521/th-mailer

## 数据库通知
必要条件
database 通知通道将通知信息存储在数据库表中。此表将包含通知类型等信息以及描述通知的 JSON 数据结构。
使用的模型必须  use HasDatabaseNotification Trait

您可以查询该表以在应用程序的用户界面中显示通知。但是，在您这样做之前，您需要创建一个数据库表来保存您的通知。您可以使用 notifications:table 命令生成具有正确表模式的 migration：

```php
php think notification:table

php think migrate:run
```
### 格式化数据库通知#

如果通知支持存储在数据库表中，则应在通知类上定义 toDatabase  方法。这个方法将接收一个 $notifiable 实体并且应该返回一个普通的 PHP 数组。 返回的数组将被编码为 JSON 并存储在 notifications 表的 data 列中。让我们看一个示例 toDatabase 方法：
```php
public function toDatabase($notifiable)
{
    return [
        'invoice_id' => $this->invoice->id,
        'amount' => $this->invoice->amount,
    ];
}
```
 访问通知

一旦通知存储在数据库中，您需要一种方便的方式从您的通知实体访问它们.
必须实现 model 之前的关联关系
```php
$user = app\model\User::find(1);

foreach ($user->notifications as $notification) {
    echo $notification->notification_type;
}
```
如果您只想检索「未读」通知，可以使用 unreadNotifications 关系。同样，这些通知将按 create_time 时间戳排序，最近的通知位于集合的开头：
```php
$user = app\model\User::find(1);

foreach ($user->unreadNotifications as $notification) {
    echo $notification->notification_type;
}

```
将通知标记为已读#
通常，您希望在用户查看通知时将其标记为 “已读”。 yzh52521\notification\Notifiable trait 提供了 markAsRead 方法，它更新通知数据库记录中的 read_time 列：

```php
$user = app\model\User::find(1);

foreach ($user->unreadNotifications as $notification) {
    $notification->markAsRead();
}
```
但是，您可以直接在通知集合上使用 markAsRead 方法，而不是循环遍历每个通知：
```php
$user->unreadNotifications->markAsRead();

```
您还可以使用批量更新查询将所有通知标记为已读，而无需从数据库中检索它们：
```php
$user = app\model\User::find(1);
$user->unreadNotifications()->update(['read_time' => now()]);
```
您可以 delete 通知以将它们从表中完全删除：

```php
$user->notifications()->delete();
```
### 短信通知 
 SMS 通知由 [Sendcloud](https://www.sendcloud.net/)  提供支持  

必要条件
$user 发件人 $key sendcloud 申请的key
```php
public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->data('Your SMS message content');
}

```
格式化短信通知
```php
public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->data('Your SMS message content');
}
```
收件人  to()

```php
public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->to('15556666666')
                ->data('Your SMS message content');
}
```
短信模版 template()

```php
public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->to('15556666666')
                ->template('40438')
                ->data('Your SMS message content');
}
```

设置为彩信 isMultimedia()
```php

public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->to('15556666666')
                ->isMultimedia()
                ->data('Your SMS message content');
}
```
设为语音短信 isVoice()

```php


    
public function toSendcloud($notifiable)
{
    return (new Sendcloud($user,$key))
                ->to('15556666666')
                ->isVoice()
                ->data('Your SMS message content');
}
```
