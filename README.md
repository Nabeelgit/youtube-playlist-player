# youtube-playlist-player
Add videos from youtube and play them without ads.

# Clone
Clone this repo from github

You will have to add your own cookies from youtube so export the cookies from youtube and put them in a txt file inside the repo folder, make sure to name the txt file "www.youtube.com_cookies.txt"

Then install youtube-video-downloader inside the folder, to do that:

Open cmd, and make sure the path is set to the repo folder, then use:

```bash
composer require athlon1600/youtube-downloader "^3.0"
```

Now run the php file either with an external application like xampp or throught cmd using:

```bash
php -S localhost:8000 -t public
```
