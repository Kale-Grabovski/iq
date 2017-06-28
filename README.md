IQ
==

Don't look at the Dockerfile, it's just temporary decision for my working machine. I know we should preferably run
each application on different container.

Run migration:

`php bin/console doctrine:migrations:migrate`

Now we are able to run console command to parse the currencies:

`php bin/console rate:parser --date=2017-03-25`

--date param is optional.

To run API requests:

```
curl http://iq.app/api/rates/eur/20170424
curl http://iq.app/api/convert/usd/eur/2/20160420
```

And sorry for duplication in the tests. I just don't understand Symfony's way to populate DB with fixtures etc.
