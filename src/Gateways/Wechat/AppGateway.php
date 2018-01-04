<?php

namespace Yansongda\Pay\Gateways\Wechat;

use Yansongda\Pay\Exceptions\InvalidArgumentException;

class AppGateway extends Wechat
{
    /**
     * get trade type config.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return string
     */
    protected function getTradeType()
    {
        return 'APP';
    }

    /**
     * pay a order.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param array $config_biz
     *
     * @return array
     */
    public function pay(array $config_biz = [])
    {
        if (is_null($this->user_config->get('open_appid'))) {
            throw new InvalidArgumentException('Missing Config -- [open_appid]');
        }

        $this->config['appid'] = $this->user_config->get('appid');

        $config_biz['appid'] = $this->user_config->get('open_appid');
        $config_biz['mch_id'] = $this->user_config->get('open_mch_id');

        $payRequest = [
            'appid'     => $this->user_config->get('open_appid'),
            'partnerid' => $this->user_config->get('open_mch_id'),
            'prepayid'  => $this->preAppOrder($config_biz)['prepay_id'],
            'timestamp' => time(),
            'noncestr'  => $this->createNonceStr(),
            'package'   => 'Sign=WXPay',
        ];
        $payRequest['sign'] = $this->getAppSign($payRequest);

        return $payRequest;
    }
}
