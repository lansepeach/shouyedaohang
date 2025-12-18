<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"
    />

    <title>后来他们...</title>
    <style>
        body {
            background-color: #eee;
            text-align: center;
            width: 100%;
            height: 100%;
            overflow: auto;
            margin: 0;
            padding: 0;
        }

        canvas {
            position: absolute;
            background-color: #eee;
            display: block;
            margin: 0 auto;
            float: left;
            z-index: 1;
            top: 0;
        }

        #toggle {
            position: absolute;
            top: 30px;
            right: 30px;
            z-index: 1000;
            height: 40px;
            width: 40px;
            font-size: 16px;
            border-radius: 50%;
            background-color: #ffffff;
            line-height: 40px;
        }
    </style>
    <link href="https://cdn.bootcss.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body>
<canvas id="canvas"></canvas>

<div style="position: fixed;
margin-top: 30px;
margin-bottom: 30px;
left: 50%;
width:90%;
height: 90%;
-webkit-transform: translateX(-50%) translateY(0%) ;
-moz-transform: translateX(-50%) translateY(0%);
-ms-transform: translateX(-50%) translateY(0%);
transform: translateX(-50%) translateY(0%);background-color:rgba(0, 0, 0, 0.7);z-index: 999;;overflow-y:auto;-webkit-overflow-scrolling: touch;">
    <div style="margin: 30px 0 0 auto;text-align: center;color: #ffffff"></div>

    <div style="float:left;position:relative;left:50%;">

        <div id="content"
             style="margin-left: 30px;margin-right: 30px;;margin-top: 30px;position:relative;left:-50%;color:#ffffff;line-height: 30px;">
        </div>

    </div>

    <div id="toggle">
        <i class="fa fa-music"></i>
    </div>
</div>


</div>

</div>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

<script>
    var pos = 0;
    var content;
    var length;
    var currentStr = '';

    $(document).ready(function () {
        content = '很多时候||我好像没什么特别的话想要跟你说||但就是希望你能在我身边||可是命运总是把我们残忍的分开||可是我感觉这一次是永远 分开了||TY ';
        length = content.length;
        textAnim();

    });


    function textAnim() {

        if (pos <= length) {
            if (pos + 3 <= length && '|' == content.substring(pos, pos + 1)) {
                currentStr = currentStr + '<br/>'
                document.getElementById("content").innerHTML = currentStr;
            } else {
                currentStr = currentStr + content.substring(pos, pos + 1)
                document.getElementById("content").innerHTML = currentStr + '_';
            }

            pos++;
            if (length > 1000) {
                document.getElementById("content").innerHTML = content;
            } else if (length > 80) {
                setTimeout("textAnim()", 100);


            } else if (length > 50) {
                setTimeout("textAnim()", 150);

            } else {

                setTimeout("textAnim()", 200);
            }
        }


    }


    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    var cw = canvas.width = window.innerWidth,
        cx = cw / 2;
    var ch = canvas.height = window.innerHeight,
        cy = ch / 2;

    ctx.fillStyle = "#000";
    var linesNum = 16;
    var linesRy = [];
    var requestId = null;

    function Line(flag) {
        this.flag = flag;
        this.a = {};
        this.b = {};
        if (flag == "v") {
            this.a.y = 0;
            this.b.y = ch;
            this.a.x = randomIntFromInterval(0, ch);
            this.b.x = randomIntFromInterval(0, ch);
        } else if (flag == "h") {
            this.a.x = 0;
            this.b.x = cw;
            this.a.y = randomIntFromInterval(0, cw);
            this.b.y = randomIntFromInterval(0, cw);
        }
        this.va = randomIntFromInterval(25, 100) / 100;
        this.vb = randomIntFromInterval(25, 100) / 100;

        this.draw = function () {
            ctx.strokeStyle = "#ccc";
            ctx.beginPath();
            ctx.moveTo(this.a.x, this.a.y);
            ctx.lineTo(this.b.x, this.b.y);
            ctx.stroke();
        };

        this.update = function () {
            if (this.flag == "v") {
                this.a.x += this.va;
                this.b.x += this.vb;
            } else if (flag == "h") {
                this.a.y += this.va;
                this.b.y += this.vb;
            }

            this.edges();
        };

        this.edges = function () {
            if (this.flag == "v") {
                if (this.a.x < 0 || this.a.x > cw) {
                    this.va *= -1;
                }
                if (this.b.x < 0 || this.b.x > cw) {
                    this.vb *= -1;
                }
            } else if (flag == "h") {
                if (this.a.y < 0 || this.a.y > ch) {
                    this.va *= -1;
                }
                if (this.b.y < 0 || this.b.y > ch) {
                    this.vb *= -1;
                }
            }
        };

    }

    for (var i = 0; i < linesNum; i++) {
        var flag = i % 2 == 0 ? "h" : "v";
        var l = new Line(flag);
        linesRy.push(l);
    }

    function Draw() {
        requestId = window.requestAnimationFrame(Draw);
        ctx.clearRect(0, 0, cw, ch);

        for (var i = 0; i < linesRy.length; i++) {
            var l = linesRy[i];
            l.draw();
            l.update();
        }
        for (var i = 0; i < linesRy.length; i++) {
            var l = linesRy[i];
            for (var j = i + 1; j < linesRy.length; j++) {
                var l1 = linesRy[j];
                Intersect2lines(l, l1);
            }
        }
    }

    function Init() {
        linesRy.length = 0;
        for (var i = 0; i < linesNum; i++) {
            var flag = i % 2 == 0 ? "h" : "v";
            var l = new Line(flag);
            linesRy.push(l);
        }

        if (requestId) {
            window.cancelAnimationFrame(requestId);
            requestId = null;
        }

        cw = canvas.width = window.innerWidth,
            cx = cw / 2;
        ch = canvas.height = window.innerHeight,
            cy = ch / 2;

        Draw();
    }
    ;

    setTimeout(function () {
        Init();

        addEventListener('resize', Init, false);
    }, 15);

    function Intersect2lines(l1, l2) {
        var p1 = l1.a,
            p2 = l1.b,
            p3 = l2.a,
            p4 = l2.b;
        var denominator = (p4.y - p3.y) * (p2.x - p1.x) - (p4.x - p3.x) * (p2.y - p1.y);
        var ua = ((p4.x - p3.x) * (p1.y - p3.y) - (p4.y - p3.y) * (p1.x - p3.x)) / denominator;
        var ub = ((p2.x - p1.x) * (p1.y - p3.y) - (p2.y - p1.y) * (p1.x - p3.x)) / denominator;
        var x = p1.x + ua * (p2.x - p1.x);
        var y = p1.y + ua * (p2.y - p1.y);
        if (ua > 0 && ub > 0) {
            markPoint({
                x: x,
                y: y
            });
        }
    }

    function markPoint(p) {
        ctx.beginPath();
        ctx.arc(p.x, p.y, 2, 0, 2 * Math.PI);
        ctx.fill();
    }

    function randomIntFromInterval(mn, mx) {
        return ~~(Math.random() * (mx - mn + 1) + mn);
    }
</script>
<div style="text-align:center;">
    <p>
        <a href="https://www.opendreamer.cn/" target="_blank">个性二维码</a>  ##这个界面我是抄袭这个w官网的
    </p>
</div>
<audio id="player" loop autoplay="autoplay"></audio>


</div>
<script  type="text/javascript" src="/public/webtpl/common/audioPlay.js"></script>
<script>
    $(document).ready(function () {
        var audio = $('audio');
        var player = $('audio')[0];
        var toggleBtn = $('#toggle');


        audio.on('play', function () {
            $('#toggle i').addClass('fa-spin');
        });

        audio.on('pause', function () {
            $('#toggle i').removeClass('fa-spin');
        });

        toggleBtn.on('click', function () {
            if (player.paused) {
                player.play();
            } else {
                player.pause();
            }
        });

        audio.attr('src', 'http://media.opendreamer.cn/kiss-the-rain60.mp3');

        $(document).ready(function(){
            var playtool = window.audioPlayTool('player')
            playtool.autoPlayMusic();
            playtool.audioAutoPlay();
        });
    });
</script>
</body>

</html>
