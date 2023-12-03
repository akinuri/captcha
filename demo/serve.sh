#!/bin/bash

# THIS IS UNTESTED
# BATCH IS TRANSPILED TO BASH USING CHATGPT

xdg-open http://localhost:8000/ &

php -S localhost:8000 -t "$(dirname "$0")"
