#!/bin/bash

rsync -avz --delete --progress ./dist/ mnemofon.it:/var/www/html/mnemofon/
