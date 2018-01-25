
# 1ME321 Kontrollverktyg
Detta verktyget kan användas för att kontrollera de vanligaste felen i Labb 2 (U1) i kursen Webbteknik 1 (1ME321).

## Live Demo
Sidan finns tillgänglig på [https://webbot.maciejsiwek.com](https://webbot.maciejsiwek.com). Använd *ms223iu* som användarnamn för att testa funktionaliteten.

## Build Setup
### Front-end
``` bash
# Install dependencies
npm install

# Change the "host" IP in webpack.config.js to your local IP
devServer: {
	 ...
	 host: "xxx.xxx.xxx.xxx",
	 port: 80,
	 ...
 }

# Serve with hot reload
npm run dev
```

### Back-end
``` bash
# Serve from the api-folder using PHPs build-in server
php -S <your-local-ip>:9000
```
