# Nas
nas utility to sync files from local machine to server

I usually have to upload new downloaded file from my local machine to my nas (and make them available to my whole LAN, Sonos or TV).

I used to use sync tools such as [FolderSync](http://www.destek.co.uk/foldersync2/), [Good Sync](https://www.goodsync.com/) ou [Syncthing](https://syncthing.net/) but remains fed up with instabilities, constant broken upgrade (good sync, I stares at you), residual files, or mandatory daemon on destination server…

I'm now back to the roots, and uses rsync, but I setup a simple wrapper in order to trigger a sync whenever I need it

[![asciicast](https://asciinema.org/a/bCMFo7tZMQVynBTQQ3tySE99a.svg)](https://asciinema.org/a/bCMFo7tZMQVynBTQQ3tySE99a)

Paths to sync, are stored as json inside `~/.nas` file

## Installation

### From build

Download binary and store it somewhere in your `$PATH`

```bash
wget -O /usr/local/sbin/nas https://raw.githubusercontent.com/loranger/nas-cli/master/builds/nas
chmod a+x /usr/local/sbin/nas
```

### From source

Clone this repository, install dependencies then install binary system-wide :

```bash
git clone git@github.com:loranger/nas-cli.git
cd nas-cli
composer install
./nas app:build
```

Move the freshly built `nas` cli somewhere in your `$PATH`

```bash
mv builds/nas /usr/local/sbin/nas
```
