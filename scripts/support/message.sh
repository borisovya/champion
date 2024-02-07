message () {
  LEVEL=$1
  MESSAGE=$2
  REWRITE_ARGUMENT_NUMBER=3
  case $LEVEL in
    info)
      COLOR=32
      ;;
    warning)
      COLOR=33
      ;;
    error)
      COLOR=31
      ;;
    *)
      LEVEL=trace
      MESSAGE=$1
      REWRITE_ARGUMENT_NUMBER=2
      COLOR=37
      ;;
  esac

  REWRITE=NO
  if [[ ${!REWRITE_ARGUMENT_NUMBER} = "rewrite" ]]; then
    REWRITE=YES
  fi

  if [[ -t 1 ]]; then
    if ! [ $REWRITE = "YES" ]; then
      echo -e "\033[1;${COLOR}m${MESSAGE}\033[0m"
    else
      echo -ne "\033[1;${COLOR}m${MESSAGE}\033[0m\r"
    fi

  else
    echo [$(date "+%Y-%m-%d %H:%M:%S")] [$LEVEL] $MESSAGE
  fi
}