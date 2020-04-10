<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%counters}}`.
 */
class m190309_083849_create_counters_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%counters}}', [
            'id'        => $this->primaryKey(),
            'code'      => $this->text(),
            'turn_on'    => $this->boolean(),
            'title'     => $this->string(),
            'sort'      => $this->integer(3)
        ], $tableOptions);

        $this->addData();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('{{%counters}}');
    }

    public function addData()
    {
       return Yii::$app->db->createCommand("
            INSERT INTO `counters` (`id`, `code`, `turn_on`, `title`, `sort`) VALUES
(1, '&lt;!--LiveInternet counter--&gt;&lt;script type=&quot;text/javascript&quot;&gt;&lt;!--\r\n            document.write(&quot;&lt;a href=\'//www.liveinternet.ru/click\' &quot;+\r\n            &quot;target=_blank&gt;&lt;img src=\'//counter.yadro.ru/hit?t38.10;r&quot;+\r\n            escape(document.referrer)+((typeof(screen)==&quot;undefined&quot;)?&quot;&quot;:\r\n            &quot;;s&quot;+screen.width+&quot;*&quot;+screen.height+&quot;*&quot;+(screen.colorDepth?\r\n            screen.colorDepth:screen.pixelDepth))+&quot;;u&quot;+escape(document.URL)+\r\n            &quot;;&quot;+Math.random()+\r\n            &quot;\' alt=\'\' title=\'LiveInternet\' &quot;+\r\n            &quot;border=\'0\' width=\'31\' height=\'31\'&gt;&lt;\\/a&gt;&quot;)\r\n            //--&gt;&lt;/script&gt;&lt;!--/LiveInternet--&gt;', 1, 'LiveInternet counter', 1),
(2, '<!-- Rating@Mail.ru counter -->\r\n<!--\r\n<script type=\"text/javascript\">\r\nvar _tmr = _tmr || [];\r\n_tmr.push({id: \"2573035\", type: \"pageView\", start: (new Date()).getTime()});\r\n(function (d, w) {\r\n   var ts = d.createElement(\"script\"); ts.type = \"text/javascript\"; ts.async = true;\r\n   ts.src = (d.location.protocol == \"https:\" ? \"https:\" : \"http:\") + \"//top-fwz1.mail.ru/js/code.js\";\r\n   var f = function () {var s = d.getElementsByTagName(\"script\")[0]; s.parentNode.insertBefore(ts, s);};\r\n   if (w.opera == \"[object Opera]\") { d.addEventListener(\"DOMContentLoaded\", f, false); } else { f(); }\r\n})(document, window);\r\n</script><noscript><div style=\"position:absolute;left:-10000px;\">\r\n<img src=\"//top-fwz1.mail.ru/counter?id=2573035;js=na\" style=\"border:0;\" height=\"1\" width=\"1\" alt=\"Рейтинг@Mail.ru\" />\r\n</div></noscript>\r\n-->\r\n<!-- //Rating@Mail.ru counter -->\r\n', 1, 'Rating@Mail.ru counter', 4),
(3, '<!-- Yandex.Metrika counter -->\r\n<script type=\"text/javascript\" >\r\n    (function (d, w, c) {\r\n        (w[c] = w[c] || []).push(function() {\r\n            try {\r\n                w.yaCounter50003317 = new Ya.Metrika2({\r\n                    id:50003317,\r\n                    clickmap:true,\r\n                    trackLinks:true,\r\n                    accurateTrackBounce:true,\r\n                    webvisor:true\r\n                });\r\n            } catch(e) { }\r\n        });\r\n\r\n        var n = d.getElementsByTagName(\"script\")[0],\r\n            s = d.createElement(\"script\"),\r\n            f = function () { n.parentNode.insertBefore(s, n); };\r\n        s.type = \"text/javascript\";\r\n        s.async = true;\r\n        s.src = \"https://mc.yandex.ru/metrika/tag.js\";\r\n\r\n        if (w.opera == \"[object Opera]\") {\r\n            d.addEventListener(\"DOMContentLoaded\", f, false);\r\n        } else { f(); }\r\n    })(document, window, \"yandex_metrika_callbacks2\");\r\n</script>\r\n<noscript><div><img src=\"https://mc.yandex.ru/watch/50003317\" style=\"position:absolute; left:-9999px;\" alt=\"\" /></div></noscript>\r\n<!-- /Yandex.Metrika counter -->', 1, 'Yandex.Metrika', 2),
(4, '<script type=\"text/javascript\">\r\n\r\n            var _gaq = _gaq || [];\r\n            _gaq.push([\'_setAccount\', \'UA-45279963-2\']);\r\n            _gaq.push([\'_trackPageview\']);\r\n\r\n            (function() {\r\n                var ga = document.createElement(\'script\'); ga.type = \'text/javascript\'; ga.async = true;\r\n                ga.src = (\'https:\' == document.location.protocol ? \'https://ssl\' : \'http://www\') + \'.google-analytics.com/ga.js\';\r\n                var s = document.getElementsByTagName(\'script\')[0]; s.parentNode.insertBefore(ga, s);\r\n            })();\r\n\r\n            </script>\r\n\r\n\r\n<!-- Google Analytics -->\r\n\r\n<script>\r\n  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){\r\n  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),\r\n  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)\r\n  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');\r\n\r\n/* Accurate bounce rate by time */\r\nif (!document.referrer ||\r\n     document.referrer.split(\'/\')[2].indexOf(location.hostname) != 0)\r\n setTimeout(function(){\r\n ga(\'send\', \'event\', \'Новый посетитель\', location.pathname);\r\n }, 15000);\r\n\r\n  ga(\'create\', \'UA-55147285-1\', \'auto\');\r\n  ga(\'send\', \'pageview\');\r\n</script>\r\n\r\n<!-- /Google Analytics -->', 1, 'Google Analytics', 3)
")->execute();
    }
}
