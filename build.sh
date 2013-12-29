#!/bin/bash

INSTALL_PATH="../.."
BUILD_PATH="./upload"

# -------------------------------------------

FILE_LIST=(
	"admin/language/english/module/priceparser.php"
	"admin/language/russian/module/priceparser.php"
	"admin/view/template/module/priceparser.tpl"
	"admin/controller/module/priceparser.php"
	"admin/model/module/priceparser.php"
	"system/third_party/priceparser"
	"system/config/priceparser"
)

# -------------------------------------------

for FILE in "${FILE_LIST[@]}"; do
	FROM="$INSTALL_PATH/$FILE"
	TO="$BUILD_PATH/$FILE"

	if [ -f $FROM ] || [ -d $FROM ]; then
		if [ ! -d $(dirname $TO) ]; then
			mkdir -p $(dirname $TO)
		fi
		cp -aurT $FROM $TO
	else
		"No such file or directory: $FROM"
	fi
done


