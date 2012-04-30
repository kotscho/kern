scm_settings = YAML.load_file(File.join('config', 'scm.yml'))
general_settings = YAML.load_file(File.join('config', 'general.yml'))

role :web, general_settings['roles']['web']
role :app, general_settings['role']['app']

set :application, general_settings['application']

set :db, general_settings['db']
set :db_user, general_settings['db_user']
set :db_passwd, general_settings['db_passwd']
set :db_backup_path, general_settings['sql_backup']
set :db_backup_file, "#{Time.now.strftime("%d-%m-%Y%H%M")}.sql"

set :use_sudo, false
# kdos - TODO: create user and group deployer on deployment server
set :user, general_settings['user']
set :group, general_settings['group']
# kdos - GIT specific password prompt flag
default_run_options[:pty] = true

# ssh options
ssh_options[:keys] = scm_settings['ssh_key'] unless scm_settings['ssh_key'].to_s.empty?

set :repository, scm_settings['repository']
set :scm, scm_settings['scm'].to_sym
set :scm_passphrase, scm_settings['scm_passphrase']
 
# Git/SVN branch for  checkout
set :branch, "master"

ssh_options[:forward_agent] = true

# kdos - deploy standards
set :deploy_via, :remote_cache #consider :copy for localhost testing
set :deploy_to, "/var/www/html/#{application}"
set :deploy_env, "development" #so...?
#  overrides
namespace :deploy do
  task :start do ; end
  task :stop do ; end
  task :restart, :roles => :app, :except => { :no_release => true } do; end
end



# if you want to clean up old releases on each deploy uncomment this:
# after "deploy:restart", "deploy:cleanup"

# if you're still using the script/reaper helper you will need
# these http://github.com/rails/irs_process_scripts

# namespace :deploy do
#   task :start do ; end
#   task :stop do ; end
#   task :restart, :roles => :app, :except => { :no_release => true } do
#     run "#{try_sudo} touch #{File.join(current_path,'tmp','restart.txt')}"
#   end
# end
