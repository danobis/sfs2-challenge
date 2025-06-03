# SFS2ILV_T2 – Secure Code

## Quick Start Guide

### Requirements
- Docker
- Docker Compose

### Setup and Start
1. Navigate to the `challenge` directory
2. Start the application
```bash
docker compose up -d
```
3. Access the challenge at: http://localhost:1950/intro

4. Later on, if you find the admin's binary, place it in the `challenge/secret-exectable` directory and run:

```bash
docker build -t secret-vuln .
docker run -it --rm secret-vuln
```

## Challenge Overview
This is a web application security challenge featuring a coffee shop e-commerce site. Your mission is to find hidden flags by exploiting security vulnerabilities in the application.

At some point, you may gain access to the admin account. There's a compiled binary hidden somewhere. It looks like an internal tool – investigate it closely.

Flag Format: *CTF{some-text}*

## Authors

- Daniel Hametner
- Tobias Link
- Tobias Moser
- Christopher Nobis

