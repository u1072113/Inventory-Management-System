@servers(['localserver' => 'rcalin@192.168.1.25'])

@task('foo', ['on' => 'localserver'])
ls
@endtask