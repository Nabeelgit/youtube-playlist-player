<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youtube playlist player</title>
    <link rel="stylesheet" type="text/css" href="./style.css">
</head>
<body>
    <noscript>
        Please enable Javascript to access this website
        <style>
            .container, .bottom {
                display: none;
            }
        </style>
    </noscript>
    <div class="container">
        <form class="video-form">
            <input placeholder="Add Youtube url..." type="text" id="video-inp" autocomplete="off" required>
            <div>
                <button type="submit" id="add-btn" class="white-btn">Add</button>
            </div>
        </form>
        <div class="videos">
            <?php
            require './vendor/autoload.php';
            use YouTube\YouTubeDownloader;
            use YouTube\Exception\YouTubeException;            
            if(isset($_COOKIE['links']) && trim($_COOKIE['links']) !== ""){
                $links = explode(',', $_COOKIE['links']);
                $youtube = new YouTubeDownloader();
                $youtube->getBrowser()->setCookieFile('./www.youtube.com_cookies.txt');
                try {
                    $i = 1;
                    foreach($links as $link){
                        $downloadOptions = $youtube->getDownloadLinks($link);
                        if ($downloadOptions->getAllFormats()) {
                            $url = $downloadOptions->getFirstCombinedFormat()->url;
                            ?>
                                <div class="video">
                                    <video width="320" height="240" id="video-<?php echo $i?>" name="<?php echo $link?>" style="margin-bottom: 1rem" controls>
                                        <source src="<?php echo $url?>">
                                    </video> 
                                    <button class="red-btn-pushable delete-btn" id="btn-<?php echo $i?>" role="button">
                                        <span class="red-btn-shadow"></span>
                                        <span class="red-btn-edge"></span>
                                        <span class="red-btn-front text">
                                            Delete
                                        </span>
                                    </button>
                                </div>
                            <?php
                            $i++;
                        } else {
                            echo 'No links found';
                        }
                    }
                } catch (YouTubeException $e) {
                    echo 'An error occured please try again later';
                }
            }
            ?>
        </div>
    </div>
    <div class="bottom">
        <button id="play-btn" class="white-btn"><svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 384 512"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/></svg></button>
    </div>
    <script>
        window.addEventListener('load', function(){
            const videos = document.querySelectorAll('video');
            if(videos.length > 0){
                document.getElementById('play-btn').addEventListener('click', function(){
                    document.getElementById('video-1').play();
                    videos.forEach((vid) => {
                        vid.addEventListener('ended', function(){
                            let num = parseInt(this.id.split('-')[1]);
                            num++;
                            let next = document.getElementById('video-'+num);
                            if(next !== null){
                                next.play();
                            }
                        })
                    })
                })
            } else {
                document.getElementById('play-btn').disabled = true;
            }
            document.querySelector('.video-form').addEventListener('submit', function(e){
                e.preventDefault();
            });
            function setCookie(cname, cvalue, exdays) {
                const d = new Date();
                d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
                let expires = "expires="+d.toUTCString();
                document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
            }

            function getCookie(cname) {
                let name = cname + "=";
                let ca = document.cookie.split(';');
                for(let i = 0; i < ca.length; i++) {
                    let c = ca[i];
                    while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                    }
                    if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                    }
                }
                return "";
            }
            function removeFromArr(arr, val){
                const index = arr.indexOf(val);
                if(index !== -1){
                    arr.splice(index, 1);
                }
                return arr;
            }
            let links = [];
            let cookie = getCookie('links');
            if(cookie !== ""){
                links = cookie.split(',');
            }
            const inp = document.getElementById('video-inp');
            document.getElementById('add-btn').addEventListener('click', function(){
                let val = inp.value.trim();
                if(val !== ''){
                    if(links.includes(val)){
                        alert('This is already in your playlist!');
                    } else {
                        links.push(val);
                        setCookie('links', links.join(','), 365);
                        window.location.reload();
                    }
                }
            })
            document.querySelectorAll('.delete-btn').forEach((one) => {
                one.addEventListener('click', function(){
                    let num = parseInt(this.id.split('-')[1]);
                    let vid = document.getElementById('video-'+num);
                    let url = vid.getAttribute('name');
                    links = removeFromArr(links, url);
                    setCookie('links', links.join(','), 365);
                    window.location.reload();
                })
            })
        })
    </script>
</body>
</html>