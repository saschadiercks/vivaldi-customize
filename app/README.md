# The application, to build a UI mod for Vivaldi

With this project you can easily select the snippets, you want to apply and turn it into a single CSS-file.
If you need to know, how to customize Vivaldi with CSS, [then head over here](/snippets)

---

## How to serve this application locally

### Use case

### Want a demo?

[Snippet builder for Vivaldi](https://demo.saschadiercks.de/vivaldi/)

## Features

- select snippets you want to apply and download them as a zip-file

### Planned Features

- tbd

## Infos for local development

### Usage of Colima
- Install Docker CLI: `brew install docker`
- Install Colima: `brew install colima`
- Start Colima: `npm run colima:start` or `colima start`
- Start the container: `npm run docker:start` or `docker-compose up`
  - (the first start can take a while)
- **Troubleshooting**: If you get an error like "limactl is running under rosetta, please reinstall lima with native arch", this means Homebrew is installed for Intel instead of Apple Silicon. Follow these steps:
  - Check your Homebrew: `which brew` (if it shows `/usr/local/bin/brew`, it's Intel)
  - Uninstall Intel Homebrew: `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/uninstall.sh)"`
  - Install Homebrew for Apple Silicon: `/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"`
  - Add to PATH: `echo 'eval "$(/opt/homebrew/bin/brew shellenv)"' >> ~/.zprofile && eval "$(/opt/homebrew/bin/brew shellenv)"`
  - Install Docker CLI: `brew install docker`
  - Install Colima: `brew install colima`
  - Start Colima: `colima start`

### Usage of docker

1. install docker on your machine (https://docs.docker.com/get-docker/)
2. head to the local repository and run `docker-compose up`
3. Wait a while until all components are loaded an the box is running. (The first start can take a while)
4. visit (http://127.0.0.1:8080/)

