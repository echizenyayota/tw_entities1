<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>もっと読む</title>
    <script scr="http://code.jquery.com/jquery-1.10.2.min.js"></script>
</head>
<body>

<?php

require_once("twitteroauth/twitteroauth.php");

$consumerKey = "YOUR_CONSUMERKEY";
$consumerSecret = "YOUR_CONSUMERSECRET";
$accessToken = "YOUR_ACCESSTOKEN";
$accessTokenSecret = "YOUR_TOKENSECRET";

$twObj = new TwitterOAuth($consumerKey,$consumerSecret,$accessToken,$accessTokenSecret);

$keywords = '平安神宮 パトレイバー';

$param = array(
    "q"=>$keywords,                  // keyword
    "lang"=>"ja",                   // language
    "count"=>100,                   // number of tweets
    "result_type"=>"recent",       // result type
    "include_entities"=>true       // entities
);

$json = $twObj->OAuthRequest(
    "https://api.twitter.com/1.1/search/tweets.json",
    "GET",
    $param);

$result = json_decode($json, true);

?>

<?php

if($result['statuses']){
    foreach($result['statuses'] as $tweet){
?>
        <?php

            // 名前へのリンク(0回以上すべての文字列)
            $tweet['user']['name'] = preg_replace("/(.*)/u", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">\\1</a>", $tweet['user']['name']);
            // ユーザー名（スクリーンネーム）へのリンク
            $tweet['user']['screen_name'] = preg_replace("/([A-Za-z0-9_]{1,15})/", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">@\\1</a>", $tweet['user']['screen_name']);

            // テキスト中の#（ハッシュタグ）へのリンク
            $tweet['text'] = preg_replace("/\s#(w*[一-龠_ぁ-ん_ァ-ヴーａ-ｚＡ-Ｚa-zA-Z0-9]+|[a-zA-Z0-9_]+|[a-zA-Z0-9_]w*)/u", " <a href=\"https://twitter.com/search/%23\\1\" target=\"twitter\">#\\1</a>", $tweet['text']);
            // テキスト中の@（スクリーンネーム）へのリンク
            $tweet['text'] = preg_replace("/(@[A-Za-z0-9_]{1,15})/", " <a href=\"https://twitter.com/\\1\" target=\"twitter\">\\1</a>", $tweet['text']);
            // テキスト中のURLへのリンク
            $tweet['text'] = preg_replace("/(http:\/\/t.co\/[a-zA-Z0-9]{10})/", "<a href=\"\\0\" target=\"_blank\">\\0</a>", $tweet['text']);
          ?>

        <ul>
          <li><?php echo date('Y-m-d H:i:s', strtotime($tweet['created_at'])); ?></li>
          <li><?php echo $tweet['user']['name']; ?></li>
          <li><?php echo $tweet['user']['screen_name']; ?></li>
          <li><img src="<?php echo $tweet['user']['profile_image_url']; ?>" /></li>
          <li><?php echo $tweet['text']; ?></li>
          <li><?php echo $tweet['id']; ?></li>
          <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
            <img src="https://si0.twimg.com/images/dev/cms/intents/icons/reply_hover.png">
            <p><a href="https://twitter.com/intent/tweet?in_reply_to= <?php echo $tweet['id']; ?>" target="_blank">Reply</a></p>
            <img src="https://si0.twimg.com/images/dev/cms/intents/icons/favorite_on.png">
            <p><a href="https://twitter.com/intent/retweet?tweet_id= <?php echo $tweet['id']; ?>" target="_blank">Retweet</a></p>
            <img src="https://si0.twimg.com/images/dev/cms/intents/icons/retweet_on.png">
            <p><a href="https://twitter.com/intent/favorite?tweet_id= <?php echo $tweet['id']; ?>" target="_blank">Favorite</a></p>
        </ul>
          <li><?php echo $tweet['entities']['media']['media_url_https']; ?></li>
  <?php } ?>
    <?php }else{ ?>
    <div class="twi_box">
        <p class="twi_tweet">関連したつぶやきがありません。</p>
    </div>
<?php } ?>
</body>
</html>