# SFS2ILV_T2 – Secure Code

## Quick Start Guide

### Requirements
- Docker
- Docker Compose
- Console to run C Executables

### Setup and Start
1. Navigate to the `challenge` directory
2. Start the application
```bash
docker compose up -d
```
3. Access the challenge at: http://localhost:1950/intro

4. Later on, if you find the admin's binary, place it in the `challenge/secret-exectable` directory and run:

```sh
./run_barista.bat # on windows
./run_barista.sh  # on linux/macos
```

## Challenge Overview
This is a web application security challenge featuring a coffee shop e-commerce site. Your mission is to find hidden flags by exploiting security vulnerabilities in the application.

At some point, you may gain access to the admin account and stumble upon a hidden compiled binary. It looks like an internal tool for baristas – investigate it closely.

Flag Format: *CTF{some-text}*

## Hints 
If you have problems finding the flags, you can find a button on the `/intro` page.

## Authors

- Daniel Hametner
- Tobias Link
- Tobias Moser
- Christopher Nobis

