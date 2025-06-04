FROM ubuntu:24.04

RUN apt update && apt install -y \
    gdb \
    nano \
    file \
    build-essential \
    unzip \
    wget \
    ca-certificates \
    cmake

RUN wget -q https://github.com/ninja-build/ninja/releases/latest/download/ninja-linux.zip \
    && unzip ninja-linux.zip -d /usr/local/bin \
    && chmod a+x /usr/local/bin/ninja \
    && rm ninja-linux.zip 

RUN apt update && apt upgrade -y

WORKDIR /home/ctf
CMD ["/bin/bash"]
