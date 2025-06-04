FROM ubuntu:24.04

RUN apt update && apt install -y \
    gdb \
    nano \
    file \
    build-essential

RUN echo 0 > /proc/sys/kernel/randomize_va_space || true

WORKDIR /home/ctf
CMD ["/bin/bash"]
