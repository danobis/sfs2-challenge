FROM --platform=linux/amd64 ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt update && apt install -y \
    gdb \
    nano \
    file \
    build-essential

RUN echo 0 > /proc/sys/kernel/randomize_va_space || true

WORKDIR /home/ctf
CMD ["/bin/bash"]
