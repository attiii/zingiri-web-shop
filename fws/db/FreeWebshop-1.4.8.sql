-- Change default currency to EUR if set to Euro
update `##settings` set currency='EUR' where currency='Euro';
