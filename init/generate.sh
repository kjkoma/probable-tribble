#!/bin/sh
# 初期ファイル生成コマンド

# Controllers
bin/cake bake Controller Framework
bin/cake bake Controller Home


# Models
bin/cake bake Model Susers
bin/cake bake Model Spasswords


