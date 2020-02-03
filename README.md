# resident-master-slave-connection

當 predis 用在常住類型的程式，例如 zero-laravel (cronjob) 時

並且在 task 中有用到 write/read 指令時，predis 預設的連線策略

會導致在一個 write 指令之後，一率使用 master connection

因此調整了 master/slave connection 策略，在實例化一個 predis 之前實例化這個 class

並且在 `option` 中指定此物件給 `replication` 參數

---

## 策略說明

此策略為，每一次的 read/write 指令都會經過 predis 內建的判斷來給予 master/slave connection 做操作

其餘行為與預設相同。
