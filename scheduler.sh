 while [ true ]
    do
      sleep 60 &
      php /www/platform/artisan schedule:run --verbose --no-interaction
      wait
    done
